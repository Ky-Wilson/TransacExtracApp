<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacOrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OrangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manager');
    }

    public function showOrangeForm()
    {
        return view('gestionnaire.orange.extraction');
    }

    public function orange(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'images.*.required' => 'Veuillez sélectionner au moins une image.',
            'images.*.max'      => 'Chaque image ne doit pas dépasser 2 Mo.',
        ]);

        $uploadedFiles = $request->file('images') ?? [];
        $processedTransactions = [];
        $errors = [];
        $processedCount = 0;

        DB::beginTransaction();

        try {
            foreach ($uploadedFiles as $index => $image) {
                $path = $image->store('temp_ocr_orange', 'public');
                $fullPath = storage_path('app/public/' . $path);

                try {
                    $text = (new TesseractOCR($fullPath))->run();
                    $text = preg_replace('/\s+/', ' ', trim($text));
                    $text = strtolower($text);

                    $transactionData = $this->extractOrangeTransaction($text);

                    Storage::disk('public')->delete($path);

                    if (!$transactionData['type']) {
                        $errors[] = "Image " . ($index + 1) . " : type de transaction non reconnu.";
                        continue;
                    }

                    // Vérification doublon (24h)
                    $existing = TransacOrange::where('user_id', auth()->id())
                        ->where('type', $transactionData['type'])
                        ->where('montant', $transactionData['montant'])
                        ->where('reference', $transactionData['reference'])
                        ->whereDate('created_at', '>=', now()->subHours(24))
                        ->first();

                    if ($existing) {
                        $errors[] = "Image " . ($index + 1) . " : transaction similaire déjà enregistrée (réf: {$transactionData['reference']}).";
                        continue;
                    }

                    TransacOrange::create([
                        'user_id'    => auth()->id(),
                        'type'       => $transactionData['type'],
                        'montant'    => $transactionData['montant'],
                        'expediteur' => $transactionData['expediteur'],
                        'reference'  => $transactionData['reference'],
                        'solde'      => $transactionData['solde'],
                        'frais'      => $transactionData['frais'],
                        'date'       => $transactionData['date'],
                        'raw_text'   => $text,
                    ]);

                    $processedTransactions[] = $transactionData;
                    $processedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Image " . ($index + 1) . " : erreur lors de l'analyse - " . $e->getMessage();
                    Storage::disk('public')->delete($path);
                }
            }

            DB::commit();

            if ($processedCount > 0) {
                $request->session()->flash('transaction_results', $processedTransactions);
                $request->session()->flash('success', "$processedCount transaction(s) Orange analysée(s) avec succès !");
            }

            if (!empty($errors)) {
                $request->session()->flash('error', implode('<br>', $errors));
            }

            return redirect()->route('manager.orange.form');

        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'Erreur globale Orange : ' . $e->getMessage());
            return redirect()->route('manager.orange.form');
        }
    }

    private function detectTransactionType($text)
    {
        if (strpos($text, 'transf') !== false || strpos($text, 'reçu un') !== false) {
            return 'transfere';
        } elseif (strpos($text, 'depot') !== false || strpos($text, 'depotot') !== false || strpos($text, 'dépôt') !== false) {
            return 'depot';
        } elseif (strpos($text, 'retrait') !== false || strpos($text, 'retra') !== false) {
            return 'retrait';
        }
        return null;
    }

    private function extractCommonData($text, &$data)
    {
        // Montant
        if (preg_match('/(?:de|dépôt|montant|transfert)\s*[:\s]*(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
            $data['montant'] = $matches[1] . ' FCFA';
        } elseif (preg_match('/(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
            $data['montant'] = $matches[1] . ' FCFA';
        }

        // Expéditeur
        if (preg_match('/07\d{8}|0\d{9}/', $text, $matches)) {
            $data['expediteur'] = $matches[0];
        }

        // Solde
        if (preg_match('/(sols?de|nouveau solde|balance?)\s*:?\s*(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
            $data['solde'] = $matches[2] . ' FCFA';
        }

        // Frais (retrait)
        if ($data['type'] === 'retrait' && preg_match('/frais(?:\s*de\s*timbre)?\s*:?\s*(\d{1,10}(?:\.\d{2})?)\s*(?:f|fcfa)/i', $text, $matches)) {
            $data['frais'] = $matches[1] . ' FCFA';
        }

        // Date
        if (preg_match('/(\d{1,2}):(\d{2})\s*(?:pm|am)/i', $text, $matches)) {
            $data['date'] = date('Y-m-d') . ' ' . $matches[1] . ':' . $matches[2] . ':00';
        } elseif (preg_match('/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})|(\d{4})-(\d{2})-(\d{2})/', $text, $matches)) {
            if (!empty($matches[3])) {
                $data['date'] = $matches[3] . '-' . $matches[2] . '-' . $matches[1];
            } elseif (!empty($matches[4])) {
                $data['date'] = $matches[4] . '-' . $matches[5] . '-' . $matches[6];
            }
        }
    }

    private function extractTransferData($text, &$data)
    {
        // Référence PP...
        if (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(pp\d{6}\.\d{4}\.[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper($matches[1]);
        } elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(pp\s*\d{6}\s*\.?\s*\d{4}\s*\.?\s*[a-z]\s*\d{5})/i', $text, $matches)) {
            $ref = preg_replace('/\s+/', '', $matches[1]);
            if (preg_match('/pp(\d{6})(\d{4})([a-z]\d{5})/i', $ref, $parts)) {
                $data['reference'] = strtoupper('PP' . $parts[1] . '.' . $parts[2] . '.' . $parts[3]);
            } else {
                $data['reference'] = strtoupper($ref);
            }
        } elseif (preg_match('/(pp\d{6}\.?\d{4}\.?[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper($matches[1]);
        } elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(pp[0-9a-z\.]{12,20})/i', $text, $matches)) {
            $data['reference'] = strtoupper(trim($matches[1]));
        }

        if (isset($data['reference'])) {
            $data['reference'] = strtoupper($data['reference']);
            if (!preg_match('/^PP\d{6}\.\d{4}\.[A-Z]\d{5}$/', $data['reference'])) {
                $data['reference'] = null;
            }
        }
    }

    private function extractDepositData($text, &$data)
    {
        $text = str_replace(['O', 'o', 'I', 'l', 'S', '5', '|'], ['0', '0', '1', '1', '5', '5', '1'], $text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[i1l]\d{6}\.\d{4}\.[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper(preg_replace('/^c[i1l]/i', 'CI', $matches[1]));
        } elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[i1l]\s*\d{6}\s*\.?\s*\d{4}\s*\.?\s*[a-z]\s*\d{5})/i', $text, $matches)) {
            $ref = preg_replace('/\s+/', '', $matches[1]);
            $ref = preg_replace('/^c[i1l]/i', 'CI', $ref);
            if (preg_match('/ci(\d{6})(\d{4})([a-z]\d{5})/i', $ref, $parts)) {
                $data['reference'] = strtoupper('CI' . $parts[1] . '.' . $parts[2] . '.' . $parts[3]);
            } else {
                $data['reference'] = strtoupper($ref);
            }
        } elseif (preg_match('/(c[i1l]\d{6}\.?\d{4}\.?[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper(preg_replace('/^c[i1l]/i', 'CI', $matches[1]));
        } elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[i1l][0-9a-z\.]{12,20})/i', $text, $matches)) {
            $data['reference'] = strtoupper(preg_replace('/^c[i1l]/i', 'CI', trim($matches[1])));
        }

        if ($data['reference']) {
            $data['reference'] = str_replace(['O', 'o'], '0', $data['reference']);
            $data['reference'] = str_replace(['I', 'l'], '1', $data['reference']);
            $data['reference'] = preg_replace('/^C1/i', 'CI', $data['reference']);
            $data['reference'] = preg_replace('/^CL/i', 'CI', $data['reference']);
            $data['reference'] = preg_replace('/^GI/i', 'CI', $data['reference']);
        }

        if ($data['reference'] && !preg_match('/^CI\d{6}\.\d{4}\.[A-Z]\d{5}$/i', $data['reference'])) {
            $data['reference'] = null;
        }
    }

    private function extractWithdrawalData($text, &$data)
    {
        $text = str_replace(['O', 'o', 'I', 'l', 'S', '5', '|'], ['0', '0', '1', '1', '5', '5', '1'], $text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[o0]\d{6}\.\d{4}\.[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper(preg_replace('/^c[o0]/i', 'CO', $matches[1]));
        } elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[o0]\s*\d{6}\s*\.?\s*\d{4}\s*\.?\s*[a-z]\s*\d{5})/i', $text, $matches)) {
            $ref = preg_replace('/\s+/', '', $matches[1]);
            $ref = preg_replace('/^c[o0]/i', 'CO', $ref);
            if (preg_match('/co(\d{6})(\d{4})([a-z]\d{5})/i', $ref, $parts)) {
                $data['reference'] = strtoupper('CO' . $parts[1] . '.' . $parts[2] . '.' . $parts[3]);
            } else {
                $data['reference'] = strtoupper($ref);
            }
        } elseif (preg_match('/(c[o0]\d{6}\.?\d{4}\.?[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper(preg_replace('/^c[o0]/i', 'CO', $matches[1]));
        } elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[o0][0-9a-z\.]{12,20})/i', $text, $matches)) {
            $data['reference'] = strtoupper(preg_replace('/^c[o0]/i', 'CO', trim($matches[1])));
        }

        if ($data['reference']) {
            $data['reference'] = str_replace(['O', 'o'], '0', $data['reference']);
            $data['reference'] = str_replace(['I', 'l'], '1', $data['reference']);
            $data['reference'] = preg_replace('/^C0/i', 'CO', $data['reference']);
            $data['reference'] = preg_replace('/^G0/i', 'CO', $data['reference']);
        }

        if ($data['reference'] && !preg_match('/^CO\d{6}\.\d{4}\.[A-Z]\d{5}$/i', $data['reference'])) {
            $data['reference'] = null;
        }
    }

    private function extractOrangeTransaction($text)
    {
        $data = [
            'type'       => null,
            'montant'    => null,
            'expediteur' => null,
            'reference'  => null,
            'solde'      => null,
            'date'       => null,
            'frais'      => null
        ];

        $data['type'] = $this->detectTransactionType($text);

        if (!$data['type']) {
            return $data;
        }

        $this->extractCommonData($text, $data);

        if ($data['type'] === 'transfere') {
            $this->extractTransferData($text, $data);
        } elseif ($data['type'] === 'depot') {
            $this->extractDepositData($text, $data);
        } elseif ($data['type'] === 'retrait') {
            $this->extractWithdrawalData($text, $data);
        }

        return $data;
    }

    public function listOrangeTransactions(Request $request)
    {
        $query = TransacOrange::where('user_id', auth()->id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('expediteur', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);
        $transactions->appends($request->all());

        return view('gestionnaire.orange.list', compact('transactions'));
    }
}
