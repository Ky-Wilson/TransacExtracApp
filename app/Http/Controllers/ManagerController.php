<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Storage;
use App\Models\TransacOrange;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manager');
    }

    public function dashboard()
    {
        return view('gestionnaire.dashboard');
    }

    public function showOrangeForm()
    {
        return view('gestionnaire.orange');
    }

   public function orange(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
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
        $text = strtolower($text); // Convertir en minuscule pour la recherche

        // Extraire les données selon le type de transaction
        $transactionData = $this->extractOrangeTransaction($text);

        // Supprimer le fichier temporaire
        Storage::disk('public')->delete($imagePath);

        if ($transactionData['type']) {
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

            // Stocker les données dans la session
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

    // Extraction de la référence selon le type
    if ($data['type'] === 'transfere' && preg_match('/reference\s+(pp[a-z0-9\.]+(?:\.[a-z0-9]+)*)/i', $text, $matches)) {
        $data['reference'] = strtoupper($matches[1]);
    } elseif ($data['type'] === 'depot' && preg_match('/reference\s+(c[i]?[a-z0-9\.]+(?:\.[a-z0-9]+)*)/i', $text, $matches)) {
        $data['reference'] = strtoupper($matches[1]);
    } elseif ($data['type'] === 'retrait' && preg_match('/reference\s+(co[a-z0-9\.]+(?:\.[a-z0-9]+)*)/i', $text, $matches)) {
        $data['reference'] = strtoupper($matches[1]);
    }

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
        $data['date'] = date('Y-m-d') . ' ' . $matches[1] . ':' . $matches[2] . ':00'; // Ajoute la date actuelle avec l'heure
    } elseif (preg_match('/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})|(\d{4})-(\d{2})-(\d{2})/', $text, $matches)) {
        if (!empty($matches[3])) {
            $data['date'] = $matches[3] . '-' . $matches[2] . '-' . $matches[1]; // Format Y-m-d
        } elseif (!empty($matches[4])) {
            $data['date'] = $matches[4] . '-' . $matches[5] . '-' . $matches[6]; // Format Y-m-d
        }
    }

    return $data;
}
}