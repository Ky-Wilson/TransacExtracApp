<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacOrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Barryvdh\DomPDF\Facade\Pdf;


class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manager');
    }

    public function dashboard()
    {
        // Fetch transaction counts and totals for the authenticated user
        $stats = TransacOrange::where('user_id', auth()->id())
            ->select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CAST(REGEXP_REPLACE(montant, "[^0-9.]", "") AS DECIMAL(15,2))) as total_amount')
            )
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Initialize stats for all types
        $transactionStats = [
            'transfere' => ['count' => 0, 'total_amount' => 0],
            'depot' => ['count' => 0, 'total_amount' => 0],
            'retrait' => ['count' => 0, 'total_amount' => 0],
        ];

        // Populate stats
        foreach ($stats as $type => $stat) {
            $transactionStats[$type] = [
                'count' => $stat->count,
                'total_amount' => $stat->total_amount,
            ];
        }

        // Fetch the 3 latest transactions for the authenticated user
        $latestTransactions = TransacOrange::where('user_id', auth()->id())
            ->select('type', 'montant', 'expediteur', 'reference', 'solde', 'frais', 'date', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('gestionnaire.dashboard', compact('transactionStats', 'latestTransactions'));
    }


public function dashboardPdf()
{
    $stats = TransacOrange::where('user_id', auth()->id())
        ->select(
            'type',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(CAST(REGEXP_REPLACE(montant, "[^0-9.]", "") AS DECIMAL(15,2))) as total_amount')
        )
        ->groupBy('type')
        ->get()
        ->keyBy('type');

    $transactionStats = [
        'transfere' => ['count' => 0, 'total_amount' => 0],
        'depot' => ['count' => 0, 'total_amount' => 0],
        'retrait' => ['count' => 0, 'total_amount' => 0],
    ];

    foreach ($stats as $type => $stat) {
        $transactionStats[$type] = [
            'count' => $stat->count,
            'total_amount' => $stat->total_amount ?? 0,
        ];
    }

    $latestTransactions = TransacOrange::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->take(15)
        ->get();

    $pdf = Pdf::loadView('gestionnaire.pdf.dashboard', compact('transactionStats', 'latestTransactions'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('dashboard-orange-money-' . now()->format('Y-m-d') . '.pdf');
}

    public function showOrangeForm()
    {
        return view('gestionnaire.orange');
    }

    public function orange(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Stocker l'image temporairement
            $image = $request->file('image');
            $imagePath = $image->store('temp_ocr', 'public');
            $fullPath = storage_path('app/public/' . $imagePath);

            // Extraire le texte avec Tesseract OCR
            $text = (new TesseractOCR($fullPath))->run();

            // Nettoyer et normaliser le texte
            $text = preg_replace('/\s+/', ' ', trim($text));
            $text = strtolower($text);

            // Extraire les données selon le type de transaction
            $transactionData = $this->extractOrangeTransaction($text);

            // Supprimer le fichier temporaire
            Storage::disk('public')->delete($imagePath);

            if ($transactionData['type']) {
                // Vérifier si une transaction similaire existe
                $existingTransaction = TransacOrange::where('user_id', auth()->id())
                    ->where('type', $transactionData['type'])
                    ->where('montant', $transactionData['montant'])
                    ->where('reference', $transactionData['reference'])
                    ->whereDate('created_at', '>=', now()->subHours(24))
                    ->first();

                if ($existingTransaction) {
                    $request->session()->flash('error', 'Une transaction similaire existe déjà. Type: ' . $transactionData['type'] . ' | Référence: ' . $transactionData['reference'] . ' | Montant: ' . $transactionData['montant']);
                    return redirect()->route('manager.orange.form');
                }

                // Enregistrer dans la table transac_orange
                TransacOrange::create([
                    'user_id' => auth()->id(),
                    'type' => $transactionData['type'],
                    'montant' => $transactionData['montant'],
                    'expediteur' => $transactionData['expediteur'],
                    'reference' => $transactionData['reference'],
                    'solde' => $transactionData['solde'],
                    'frais' => $transactionData['frais'],
                    'date' => $transactionData['date'],
                    'raw_text' => $text,
                ]);

                $request->session()->flash('transaction_data', $transactionData);
                $request->session()->flash('success', 'Transaction enregistrée avec succès');

                return redirect()->route('manager.orange.form');
            } else {
                $request->session()->flash('error', 'Type de transaction non reconnu');
                return redirect()->route('manager.orange.form');
            }

        } catch (\Exception $e) {
            $request->session()->flash('error', 'Erreur lors de l\'analyse de l\'image: ' . $e->getMessage());
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
        // Extraction du montant
        if (preg_match('/(?:de|dépôt|montant|transfert)\s*[:\s]*(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
            $data['montant'] = $matches[1] . ' FCFA';
        } elseif (preg_match('/(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
            $data['montant'] = $matches[1] . ' FCFA';
        }

        // Extraction du numéro expéditeur
        if (preg_match('/07\d{8}|0\d{9}/', $text, $matches)) {
            $data['expediteur'] = $matches[0];
        }

        // Extraction du solde
        if (preg_match('/(sols?de|nouveau solde|balance?)\s*:?\s*(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
            $data['solde'] = $matches[2] . ' FCFA';
        }

        // Extraction des frais (pour retrait uniquement)
        if ($data['type'] === 'retrait' && preg_match('/frais(?:\s*de\s*timbre)?\s*:?\s*(\d{1,10}(?:\.\d{2})?)\s*(?:f|fcfa)/i', $text, $matches)) {
            $data['frais'] = $matches[1] . ' FCFA';
        }

        // Extraction de la date
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
        // Pattern 1: Format complet PP251005.1719.A00922
        if (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(pp\d{6}\.\d{4}\.[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper($matches[1]);
        }
        // Pattern 2: Avec espaces ou points manquants PP251005 1719 A00922
        elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(pp\s*\d{6}\s*\.?\s*\d{4}\s*\.?\s*[a-z]\s*\d{5})/i', $text, $matches)) {
            $ref = preg_replace('/\s+/', '', $matches[1]);
            if (preg_match('/pp(\d{6})(\d{4})([a-z]\d{5})/i', $ref, $parts)) {
                $data['reference'] = strtoupper('PP' . $parts[1] . '.' . $parts[2] . '.' . $parts[3]);
            } else {
                $data['reference'] = strtoupper($ref);
            }
        }
        // Pattern 3: PP suivi de caractères (minimum 15 caractères)
        elseif (preg_match('/(pp\d{6}\.?\d{4}\.?[a-z]\d{5})/i', $text, $matches)) {
            $data['reference'] = strtoupper($matches[1]);
        }
        // Pattern 4: Fallback for PP references
        elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(pp[0-9a-z\.]{12,20})/i', $text, $matches)) {
            $data['reference'] = strtoupper(trim($matches[1]));
        }

        // Validation
        /* if ($data['reference'] && !preg_match('/^PP\d{6}\.\d{4}\.[A-Z]\d{5}$/i', $data['reference'])) {
            $data['reference'] = null;
        } */
       // Normalisation finale et validation
if (isset($data['reference'])) {
    $data['reference'] = strtoupper($data['reference']);
    if (!preg_match('/^PP\d{6}\.\d{4}\.[A-Z]\d{5}$/', $data['reference'])) {
        $data['reference'] = null;
    }
}
    }

   private function extractDepositData($text, &$data)
{
    // Normalize common OCR errors in text
    $text = str_replace(['O', 'o', 'I', 'l', 'S', '5', '|'], ['0', '0', '1', '1', '5', '5', '1'], $text);
    $text = preg_replace('/\s+/', ' ', trim($text));

    // Pattern 1: Format complet CI161214.1217.A50760
    if (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[i1l]\d{6}\.\d{4}\.[a-z]\d{5})/i', $text, $matches)) {
        $data['reference'] = strtoupper(preg_replace('/^c[i1l]/i', 'CI', $matches[1]));
    }
    // Pattern 2: Avec espaces ou points manquants CI161214 1217 A50760
    elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[i1l]\s*\d{6}\s*\.?\s*\d{4}\s*\.?\s*[a-z]\s*\d{5})/i', $text, $matches)) {
        $ref = preg_replace('/\s+/', '', $matches[1]);
        $ref = preg_replace('/^c[i1l]/i', 'CI', $ref);
        if (preg_match('/ci(\d{6})(\d{4})([a-z]\d{5})/i', $ref, $parts)) {
            $data['reference'] = strtoupper('CI' . $parts[1] . '.' . $parts[2] . '.' . $parts[3]);
        } else {
            $data['reference'] = strtoupper($ref);
        }
    }
    // Pattern 3: CI suivi de caractères (minimum 15 caractères)
    elseif (preg_match('/(c[i1l]\d{6}\.?\d{4}\.?[a-z]\d{5})/i', $text, $matches)) {
        $data['reference'] = strtoupper(preg_replace('/^c[i1l]/i', 'CI', $matches[1]));
    }
    // Pattern 4: Fallback for CI references
    elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[i1l][0-9a-z\.]{12,20})/i', $text, $matches)) {
        $data['reference'] = strtoupper(preg_replace('/^c[i1l]/i', 'CI', trim($matches[1])));
    }

    // Additional normalizations
    if ($data['reference']) {
        $data['reference'] = str_replace(['O', 'o'], '0', $data['reference']);
        $data['reference'] = str_replace(['I', 'l'], '1', $data['reference']);
        $data['reference'] = preg_replace('/^C1/i', 'CI', $data['reference']);
        $data['reference'] = preg_replace('/^CL/i', 'CI', $data['reference']);
        $data['reference'] = preg_replace('/^GI/i', 'CI', $data['reference']);
    }

    // Validation
    if ($data['reference'] && !preg_match('/^CI\d{6}\.\d{4}\.[A-Z]\d{5}$/i', $data['reference'])) {
        $data['reference'] = null;
    }
}

private function extractWithdrawalData($text, &$data)
{
    // Normalize common OCR errors in text
    $text = str_replace(['O', 'o', 'I', 'l', 'S', '5', '|'], ['0', '0', '1', '1', '5', '5', '1'], $text);
    $text = preg_replace('/\s+/', ' ', trim($text));

    // Pattern 1: Format complet CO161214.1217.A50760
    if (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[o0]\d{6}\.\d{4}\.[a-z]\d{5})/i', $text, $matches)) {
        $data['reference'] = strtoupper(preg_replace('/^c[o0]/i', 'CO', $matches[1]));
    }
    // Pattern 2: Avec espaces ou points manquants CO161214 1217 A50760
    elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[o0]\s*\d{6}\s*\.?\s*\d{4}\s*\.?\s*[a-z]\s*\d{5})/i', $text, $matches)) {
        $ref = preg_replace('/\s+/', '', $matches[1]);
        $ref = preg_replace('/^c[o0]/i', 'CO', $ref);
        if (preg_match('/co(\d{6})(\d{4})([a-z]\d{5})/i', $ref, $parts)) {
            $data['reference'] = strtoupper('CO' . $parts[1] . '.' . $parts[2] . '.' . $parts[3]);
        } else {
            $data['reference'] = strtoupper($ref);
        }
    }
    // Pattern 3: CO suivi de caractères (minimum 15 caractères)
    elseif (preg_match('/(c[o0]\d{6}\.?\d{4}\.?[a-z]\d{5})/i', $text, $matches)) {
        $data['reference'] = strtoupper(preg_replace('/^c[o0]/i', 'CO', $matches[1]));
    }
    // Pattern 4: Fallback for CO references
    elseif (preg_match('/(?:reference|référence|ref\.?)\s*:?\s*(c[o0][0-9a-z\.]{12,20})/i', $text, $matches)) {
        $data['reference'] = strtoupper(preg_replace('/^c[o0]/i', 'CO', trim($matches[1])));
    }

    // Additional normalizations
    if ($data['reference']) {
        $data['reference'] = str_replace(['O', 'o'], '0', $data['reference']);
        $data['reference'] = str_replace(['I', 'l'], '1', $data['reference']);
        $data['reference'] = preg_replace('/^C0/i', 'CO', $data['reference']);
        $data['reference'] = preg_replace('/^G0/i', 'CO', $data['reference']);
    }

    // Validation
    if ($data['reference'] && !preg_match('/^CO\d{6}\.\d{4}\.[A-Z]\d{5}$/i', $data['reference'])) {
        $data['reference'] = null;
    }
}

    private function extractOrangeTransaction($text)
    {
        $data = [
            'type' => null,
            'montant' => null,
            'expediteur' => null,
            'reference' => null,
            'solde' => null,
            'date' => null,
            'frais' => null
        ];

        // Détecter le type de transaction
        $data['type'] = $this->detectTransactionType($text);

        if (!$data['type']) {
            return $data;
        }

        // Extraire les données communes
        $this->extractCommonData($text, $data);

        // Extraire la référence selon le type
        if ($data['type'] === 'transfere') {
            $this->extractTransferData($text, $data);
        } elseif ($data['type'] === 'depot') {
            $this->extractDepositData($text, $data);
        } elseif ($data['type'] === 'retrait') {
            $this->extractWithdrawalData($text, $data);
        }

        return $data;
    }

    // public function listOrangeTransactions()
    // {
    //     // Fetch transactions for the authenticated user with pagination
    //     $transactions = TransacOrange::where('user_id', auth()->id())
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     // Pass the transactions to the view
    //     return view('gestionnaire.orange_transactions', compact('transactions'));
    // }
    public function listOrangeTransactions(Request $request)
{
    $query = TransacOrange::where('user_id', auth()->id());

    // Filtre par type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Recherche par référence ou expéditeur
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('reference', 'like', "%{$search}%")
              ->orWhere('expediteur', 'like', "%{$search}%");
        });
    }

    // Filtre par plage de dates (sur le champ date)
    if ($request->filled('date_from')) {
        $query->whereDate('date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('date', '<=', $request->date_to);
    }

    $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

    // Preserve les filtres dans les liens de pagination
    $transactions->appends($request->all());

    return view('gestionnaire.orange_transactions', compact('transactions'));
}
}