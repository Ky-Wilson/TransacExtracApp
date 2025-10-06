<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacOrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

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

    // Populate stats - GARDEZ LES VALEURS NUMÉRIQUES
    foreach ($stats as $type => $stat) {
        $transactionStats[$type] = [
            'count' => $stat->count,
            'total_amount' => $stat->total_amount, // Ne pas formater ici
        ];
    }

    return view('gestionnaire.dashboard', compact('transactionStats'));
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
            // Vérifier si une transaction similaire existe (même type, montant et date proche)
            $existingTransaction = TransacOrange::where('user_id', auth()->id())
                ->where('type', $transactionData['type'])
                ->where('montant', $transactionData['montant'])
                ->where('reference', $transactionData['reference'])
                ->whereDate('created_at', '>=', now()->subHours(24)) // Dans les dernières 24h
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

    // Détection du type de transaction
    if (strpos($text, 'transf') !== false || strpos($text, 'reçu un') !== false) {
        $data['type'] = 'transfere';
    } elseif (strpos($text, 'depot') !== false || strpos($text, 'depotot') !== false || strpos($text, 'dépôt') !== false) {
        $data['type'] = 'depot';
    } elseif (strpos($text, 'retrait') !== false || strpos($text, 'retra') !== false) {
        $data['type'] = 'retrait';
    }

    if (!$data['type']) {
        return $data; // Type non reconnu
    }

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

    // ==================================================================
    // == SECTION CORRIGÉE : EXTRACTION DE LA RÉFÉRENCE ==
    // ==================================================================
    
    $keywordPattern = '(?:reference|référence|ref\.|ref)\s*:?\s*';

    if ($data['type'] === 'transfere') {
        // Pattern pour transfere: PP suivi de lettres, chiffres, points
        if (preg_match('/' . $keywordPattern . '(pp[a-z0-9\.]+)/i', $text, $matches) || preg_match('/(pp[a-z0-9\.]{15,})/i', $text, $matches)) {
            $data['reference'] = strtoupper($matches[1]);
        }
    } 
    // --- CORRECTION POUR DÉPÔT ET RETRAIT ---
    elseif ($data['type'] === 'depot') {
        // Pattern flexible: CI/C1/CL suivi de LETTRES, chiffres et points
        if (preg_match('/' . $keywordPattern . '(c[il1][a-z0-9\.]+)/i', $text, $matches) || preg_match('/(c[il1][a-z0-9\.]{15,})/i', $text, $matches)) {
            // Remplace C1 ou CL par CI au début de la chaîne pour normaliser
            $data['reference'] = preg_replace('/^C[L1]/', 'CI', strtoupper($matches[1]));
        }
    } elseif ($data['type'] === 'retrait') {
        // Pattern flexible: CO/C0 suivi de LETTRES, chiffres et points
        if (preg_match('/' . $keywordPattern . '(c[o0][a-z0-9\.]+)/i', $text, $matches) || preg_match('/(c[o0][a-z0-9\.]{15,})/i', $text, $matches)) {
            // Remplace C0 par CO au début de la chaîne pour normaliser
            $data['reference'] = preg_replace('/^C0/', 'CO', strtoupper($matches[1]));
        }
    }
    // ==================================================================
    // == FIN DE LA SECTION CORRIGÉE ==
    // ==================================================================

    // Extraction du solde
    if (preg_match('/(sols?de|nouveau solde|balance?)\s*:?\s*(\d{1,10}(?:\.\d{2})?)\s*(?:fcfa|f)/i', $text, $matches)) {
        $data['solde'] = $matches[2] . ' FCFA';
    }

    // Extraction des frais uniquement pour retrait
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

    return $data;
}
    public function listOrangeTransactions()
    {
        // Fetch transactions for the authenticated user with pagination
        $transactions = TransacOrange::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate with 10 transactions per page

        // Pass the transactions to the view
        return view('gestionnaire.orange_transactions', compact('transactions'));
    }
}