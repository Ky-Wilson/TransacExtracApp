<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacMtn;
use App\Models\TransacOrange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super_admin');
    }

    public function dashboard()
{
    // Total admins (compagnies)
    $totalAdmins = User::where('role', 'admin')->count();

    // Total gestionnaires
    $totalManagers = User::where('role', 'gestionnaire')->count();

    // Gestionnaires actifs/inactifs
    $activeManagers = User::where('role', 'gestionnaire')->where('status', true)->count();
    $inactiveManagers = $totalManagers - $activeManagers;

    // Transactions globales (toutes compagnies)
    $totalTransactionsOrange = TransacOrange::count();
    $totalTransactionsMtn     = TransacMtn::count();

    $totalAmountOrange = (float) TransacOrange::sum('montant');
    $totalAmountMtn    = (float) TransacMtn::sum('montant');
    // Dernières transactions (exemple : 5 dernières combinées)
    $recentTransactions = TransacOrange::select('id', 'date', 'montant', 'user_id', DB::raw("'Orange' as type"))
        ->union(TransacMtn::select('id', 'date', 'montant', 'user_id', DB::raw("'MTN' as type")))
        ->orderBy('date', 'desc')
        ->take(5)
        ->get();

    // Taux OCR global Orange (exemple)
    $successfulOCR = TransacOrange::whereNotNull('reference')->count();
    $ocrSuccessRate = $totalTransactionsOrange > 0 ? round(($successfulOCR / $totalTransactionsOrange) * 100, 1) : 0;

    return view('superadmin.dashboard', compact(
        'totalAdmins', 'totalManagers', 'activeManagers', 'inactiveManagers',
        'totalTransactionsOrange', 'totalTransactionsMtn',
        'totalAmountOrange', 'totalAmountMtn',
        'recentTransactions', 'ocrSuccessRate'
    ));
}

    public function manageManagers()
    {
        // Récupérer les gestionnaires avec leur admin associé via company_id
        $managers = User::where('role', 'gestionnaire')
            ->with(['admin' => function ($query) {
                $query->where('role', 'admin')->first(); // Récupère le premier admin pour cette company_id
            }])
            ->paginate(10);

        return view('superadmin.manager.managerList', compact('managers')); // Vue pour les gestionnaires
    }

    public function manageAdmins()
    {
        // Récupérer les admins avec le nombre de gestionnaires associés
        $admins = User::where('role', 'admin')
            ->withCount(['managers' => function ($query) {
                $query->where('role', 'gestionnaire');
            }])
            ->paginate(10);

        return view('superadmin.admin.adminList', compact('admins')); // Vue pour les admins
    }

    public function updateAdminStatus(Request $request, $adminId)
    {
        $admin = User::findOrFail($adminId);
        $status = $request->input('status') === '1';

        // Mettre à jour le statut de l'admin
        $admin->status = $status;
        $admin->save();

        // Mettre à jour le statut des gestionnaires associés (via company_id ou admin_id)
        $managers = User::where('role', 'gestionnaire')
            ->where('company_id', $admin->company_id) // Ou where('admin_id', $admin->id) si utilisé
            ->update(['status' => $status]);

        return response()->json(['success' => true, 'message' => 'Statut mis à jour avec succès pour l\'admin et ses gestionnaires.']);
    }
}
