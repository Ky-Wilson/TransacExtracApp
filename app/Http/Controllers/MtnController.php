<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacMtn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Carbon\Carbon;

class MtnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manager');
    }

    public function showMtnForm()
    {
        return view('gestionnaire.mtn.extraction');
    }

    public function mtn(Request $request)
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
                $path = $image->store('temp_ocr_mtn', 'public');
                $fullPath = storage_path('app/public/' . $path);

                try {
                    $text = (new TesseractOCR($fullPath))->run();
                    $text = preg_replace('/\s+/', ' ', trim($text));
                    $text = strtolower($text);

                    $transactionData = $this->extractMtnTransaction($text);

                    Storage::disk('public')->delete($path);

                    if (!$transactionData['type']) {
                        $errors[] = "Image " . ($index + 1) . " : type de transaction MTN non reconnu.";
                        continue;
                    }

                    // Vérification doublon (24h)
                    $existing = TransacMtn::where('user_id', auth()->id())
                        ->where('type', $transactionData['type'])
                        ->where('montant', $transactionData['montant'])
                        ->where('reference', $transactionData['reference'])
                        ->whereDate('created_at', '>=', now()->subHours(24))
                        ->first();

                    if ($existing) {
                        $errors[] = "Image " . ($index + 1) . " : transaction similaire déjà enregistrée (ID Tr: {$transactionData['reference']}).";
                        continue;
                    }

                    // Date : on force nullable si invalide
                    $date = null;
                    if (!empty($transactionData['date'])) {
                        try {
                            $date = Carbon::parse($transactionData['date']);
                        } catch (\Exception $e) {
                            $errors[] = "Image " . ($index + 1) . " : date invalide ignorée (" . $transactionData['date'] . ").";
                        }
                    }

                    TransacMtn::create([
                        'user_id'    => auth()->id(),
                        'type'       => $transactionData['type'],
                        'montant'    => $transactionData['montant'],
                        'expediteur' => $transactionData['expediteur'],
                        'reference'  => $transactionData['reference'],
                        'solde'      => $transactionData['solde'],
                        'frais'      => $transactionData['frais'],
                        'date'       => $date,
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
                $request->session()->flash('transaction_results_mtn', $processedTransactions);
                $request->session()->flash('success', "$processedCount transaction(s) MTN analysée(s) avec succès !");
            }

            if (!empty($errors)) {
                $request->session()->flash('error', implode('<br>', $errors));
            }

            return redirect()->route('manager.mtn.form');

        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'Erreur globale MTN : ' . $e->getMessage());
            return redirect()->route('manager.mtn.form');
        }
    }

    private function extractMtnTransaction($text)
    {
        $data = [
            'type'       => null,
            'montant'    => null,
            'expediteur' => null,
            'reference'  => null,
            'solde'      => null,
            'frais'      => null,
            'date'       => null
        ];

        // Détection du type (plus robuste)
        if (stripos($text, 'transfere') !== false || stripos($text, 'vous avez transfere') !== false) {
            $data['type'] = 'transfere';
        } elseif (stripos($text, 'recu') !== false || stripos($text, 'vous avez recu') !== false) {
            $data['type'] = 'depot';
        } elseif (stripos($text, 'retire') !== false || stripos($text, 'vous avez retire') !== false) {
            $data['type'] = 'retrait';
        }

        if (!$data['type']) return $data;

        // Montant
        if (preg_match('/(?:transfere|recu|retire)\s*(\d{1,})\s*fcfa/i', $text, $m)) {
            $data['montant'] = $m[1] . ' FCFA';
        } elseif (preg_match('/(\d{1,})\s*fcfa/i', $text, $m)) {
            $data['montant'] = $m[1] . ' FCFA';
        }

        // Expéditeur (225 + 8 chiffres)
        if (preg_match('/(?:au|sur votre compte)\s*(225\d{8})/i', $text, $m)) {
            $data['expediteur'] = $m[1];
        }

        // Référence (ID Tr ou ID Transaction)
        if (preg_match('/id tr(?:ansaction)?\s*:\s*(\d{10,})/i', $text, $m)) {
            $data['reference'] = $m[1];
        } elseif (preg_match('/id transaction\s*:\s*(\d{10,})/i', $text, $m)) {
            $data['reference'] = $m[1];
        }

        // Solde
        if (preg_match('/solde\s*(actuel|est de|nouveau solde)\s*:\s*(\d{1,})\s*fcfa/i', $text, $m)) {
            $data['solde'] = $m[2] . ' FCFA';
        }

        // Frais (retrait)
        if ($data['type'] === 'retrait' && preg_match('/frais\s*:\s*(\d{1,})\s*fcfa/i', $text, $m)) {
            $data['frais'] = $m[1] . ' FCFA';
        }

        // Date - Version corrigée et robuste
        if (preg_match('/(\d{2})-(\d{2})-(\d{4})\s*(\d{2}):(\d{2}):(\d{2})/', $text, $m)) {
            $year = $m[3];
            // Protection contre les années invalides (ex: 202025 → on limite à 20xx)
            if ($year >= 2000 && $year <= date('Y') + 1) {
                $data['date'] = "{$year}-{$m[2]}-{$m[1]} {$m[4]}:{$m[5]}:{$m[6]}";
            }
        } elseif (preg_match('/(\d{2})[\/-](\d{2})[\/-](\d{4})\s*(\d{2}):(\d{2})/', $text, $m)) {
            $year = $m[3];
            if ($year >= 2000 && $year <= date('Y') + 1) {
                $data['date'] = "{$year}-{$m[2]}-{$m[1]} {$m[4]}:{$m[5]}:00";
            }
        }

        return $data;
    }

    public function listMtnTransactions(Request $request)
    {
        $query = TransacMtn::where('user_id', auth()->id());

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

        return view('gestionnaire.mtn.list', compact('transactions'));
    }
}
