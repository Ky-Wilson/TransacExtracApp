<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacOrange;
use App\Models\TransacMtn;
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
        // Orange Money
        $orangeStatsRaw = TransacOrange::where('user_id', auth()->id())
            ->select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CAST(REGEXP_REPLACE(montant, "[^0-9.]", "") AS DECIMAL(15,2))) as total_amount')
            )
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $orangeStats = [
            'transfere' => ['count' => 0, 'total_amount' => 0],
            'depot'     => ['count' => 0, 'total_amount' => 0],
            'retrait'   => ['count' => 0, 'total_amount' => 0],
        ];

        foreach ($orangeStatsRaw as $type => $stat) {
            $orangeStats[$type] = [
                'count' => $stat->count,
                'total_amount' => $stat->total_amount ?? 0,
            ];
        }

        // MTN MoMo
        $mtnStatsRaw = TransacMtn::where('user_id', auth()->id())
            ->select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CAST(REGEXP_REPLACE(montant, "[^0-9.]", "") AS DECIMAL(15,2))) as total_amount')
            )
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $mtnStats = [
            'transfere' => ['count' => 0, 'total_amount' => 0],
            'depot'     => ['count' => 0, 'total_amount' => 0],
            'retrait'   => ['count' => 0, 'total_amount' => 0],
        ];

        foreach ($mtnStatsRaw as $type => $stat) {
            $mtnStats[$type] = [
                'count' => $stat->count,
                'total_amount' => $stat->total_amount ?? 0,
            ];
        }

        // Dernières transactions combinées (5 dernières, Orange + MTN)
        $orangeLatest = TransacOrange::where('user_id', auth()->id())
            ->selectRaw("
                'orange' as operator,
                type,
                montant,
                expediteur,
                reference,
                date,
                created_at
            ")
            ->limit(5);

        $mtnLatest = TransacMtn::where('user_id', auth()->id())
            ->selectRaw("
                'mtn' as operator,
                type,
                montant,
                expediteur,
                reference,
                date,
                created_at
            ")
            ->limit(5);

        $latestTransactions = $orangeLatest->union($mtnLatest)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('gestionnaire.dashboard', compact(
            'orangeStats',
            'mtnStats',
            'latestTransactions'
        ));
    }

    public function dashboardPdf()
    {
        // Même logique que dashboard() mais pour PDF
        $orangeStatsRaw = TransacOrange::where('user_id', auth()->id())
            ->select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CAST(REGEXP_REPLACE(montant, "[^0-9.]", "") AS DECIMAL(15,2))) as total_amount')
            )
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $orangeStats = [
            'transfere' => ['count' => 0, 'total_amount' => 0],
            'depot'     => ['count' => 0, 'total_amount' => 0],
            'retrait'   => ['count' => 0, 'total_amount' => 0],
        ];

        foreach ($orangeStatsRaw as $type => $stat) {
            $orangeStats[$type] = [
                'count' => $stat->count,
                'total_amount' => $stat->total_amount ?? 0,
            ];
        }

        $mtnStatsRaw = TransacMtn::where('user_id', auth()->id())
            ->select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CAST(REGEXP_REPLACE(montant, "[^0-9.]", "") AS DECIMAL(15,2))) as total_amount')
            )
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $mtnStats = [
            'transfere' => ['count' => 0, 'total_amount' => 0],
            'depot'     => ['count' => 0, 'total_amount' => 0],
            'retrait'   => ['count' => 0, 'total_amount' => 0],
        ];

        foreach ($mtnStatsRaw as $type => $stat) {
            $mtnStats[$type] = [
                'count' => $stat->count,
                'total_amount' => $stat->total_amount ?? 0,
            ];
        }

        $latestTransactions = TransacOrange::where('user_id', auth()->id())
            ->selectRaw("'orange' as operator, type, montant, expediteur, reference, date, created_at")
            ->union(
                TransacMtn::where('user_id', auth()->id())
                    ->selectRaw("'mtn' as operator, type, montant, expediteur, reference, date, created_at")
            )
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $pdf = Pdf::loadView('gestionnaire.pdf.dashboard', compact('orangeStats', 'mtnStats', 'latestTransactions'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('dashboard-transacextract-' . now()->format('Y-m-d') . '.pdf');
    }
}
