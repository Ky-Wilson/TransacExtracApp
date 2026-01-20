<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacOrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'depot'     => ['count' => 0, 'total_amount' => 0],
            'retrait'   => ['count' => 0, 'total_amount' => 0],
        ];

        foreach ($stats as $type => $stat) {
            $transactionStats[$type] = [
                'count' => $stat->count,
                'total_amount' => $stat->total_amount ?? 0,
            ];
        }

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
            'depot'     => ['count' => 0, 'total_amount' => 0],
            'retrait'   => ['count' => 0, 'total_amount' => 0],
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
}
