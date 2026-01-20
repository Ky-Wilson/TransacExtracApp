<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransacOrange;
use App\Models\TransacMtn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company_admin');
    }

    // public function dashboard()
    // {
    //     $companyId = auth()->user()->company_id;

    //     // KPI Gestionnaires (inchangé)
    //     $totalManagers    = User::where('role', 'gestionnaire')->where('company_id', $companyId)->count();
    //     $activeManagers   = User::where('role', 'gestionnaire')->where('company_id', $companyId)->where('status', true)->count();
    //     $inactiveManagers = $totalManagers - $activeManagers;

    //     $recentManagers = User::where('role', 'gestionnaire')
    //         ->where('company_id', $companyId)
    //         ->orderBy('created_at', 'desc')
    //         ->take(5)
    //         ->get(['id', 'name', 'email', 'status', 'created_at', 'avatar']);

    //     // KPI Orange
    //     $orangeQuery = TransacOrange::query()
    //         ->join('users', 'transac_orange.user_id', '=', 'users.id')
    //         ->where('users.company_id', $companyId);

    //     $totalTransactionsOrange = (clone $orangeQuery)->count();

    //     $todayTransactionsOrange = (clone $orangeQuery)
    //         ->whereDate('transac_orange.date', today())
    //         ->count();

    //     // Montant → conversion explicite
    //     $totalAmountOrange = (float) (clone $orangeQuery)->sum('transac_orange.montant');

    //     $lastOrange = (clone $orangeQuery)
    //         ->orderBy('transac_orange.date', 'desc')
    //         ->first(['transac_orange.date', 'transac_orange.montant']);

    //     $successfulOCR = (clone $orangeQuery)->whereNotNull('transac_orange.reference')->count();

    //     $ocrSuccessRate = $totalTransactionsOrange > 0
    //         ? round(($successfulOCR / $totalTransactionsOrange) * 100, 1)
    //         : 0;

    //     return view('admin.dashboard', compact(
    //         'totalManagers', 'activeManagers', 'inactiveManagers', 'recentManagers',
    //         'totalTransactionsOrange', 'todayTransactionsOrange',
    //         'totalAmountOrange', 'lastOrange',
    //         'ocrSuccessRate'
    //     ));
    // }



    public function dashboard()
    {
        $companyId = auth()->user()->company_id;

        // KPI Gestionnaires (inchangé)
        $totalManagers    = User::where('role', 'gestionnaire')->where('company_id', $companyId)->count();
        $activeManagers   = User::where('role', 'gestionnaire')->where('company_id', $companyId)->where('status', true)->count();
        $inactiveManagers = $totalManagers - $activeManagers;

        $recentManagers = User::where('role', 'gestionnaire')
            ->where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'name', 'email', 'status', 'created_at', 'avatar']);

        // KPI Orange (inchangé)
        $orangeQuery = TransacOrange::query()
            ->join('users', 'transac_orange.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId);

        $totalTransactionsOrange = (clone $orangeQuery)->count();

        $todayTransactionsOrange = (clone $orangeQuery)
            ->whereDate('transac_orange.date', today())
            ->count();

        $totalAmountOrange = (float) (clone $orangeQuery)->sum('transac_orange.montant');

        $lastOrange = (clone $orangeQuery)
            ->orderBy('transac_orange.date', 'desc')
            ->first(['transac_orange.date', 'transac_orange.montant']);

        $successfulOCR = (clone $orangeQuery)->whereNotNull('transac_orange.reference')->count();

        $ocrSuccessRate = $totalTransactionsOrange > 0
            ? round(($successfulOCR / $totalTransactionsOrange) * 100, 1)
            : 0;

        // KPI MTN (nouveau)
        $mtnQuery = TransacMtn::query()
            ->join('users', 'transac_mtn.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId);

        $totalTransactionsMtn = (clone $mtnQuery)->count();

        $todayTransactionsMtn = (clone $mtnQuery)
            ->whereDate('transac_mtn.date', today())
            ->count();

        $totalAmountMtn = (float) (clone $mtnQuery)->sum('transac_mtn.montant');

        $lastMtn = (clone $mtnQuery)
            ->orderBy('transac_mtn.date', 'desc')
            ->first(['transac_mtn.date', 'transac_mtn.montant']);

        return view('admin.dashboard', compact(
            'totalManagers',
            'activeManagers',
            'inactiveManagers',
            'recentManagers',
            'totalTransactionsOrange',
            'todayTransactionsOrange',
            'totalAmountOrange',
            'lastOrange',
            'ocrSuccessRate',
            'totalTransactionsMtn',
            'todayTransactionsMtn',
            'totalAmountMtn',
            'lastMtn'
        ));
    }

    public function showCreateManagerForm()
    {
        return view('admin.manager.create');
    }
    public function createManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                }),
            ],
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'Cet email est déjà utilisé pour un gestionnaire dans votre entreprise.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'gestionnaire',
            'status' => true,
            'company_id' => auth()->user()->company_id,
            'avatar' => auth()->user()->avatar, // Hérite de l'avatar de l'admin
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Gestionnaire créé avec succès.');
    }
    public function manageManagers()
    {
        $managers = User::where('role', 'gestionnaire')
            ->where('company_id', auth()->user()->company_id)
            ->paginate(5); // 5 éléments par page

        return view('admin.manager.list', compact('managers'));
    }
    public function updateManagerStatus(Request $request, User $manager)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $manager->update(['status' => $request->status]);

        // Return JSON response instead of redirect
        return response()->json([
            'success' => true,
            'message' => 'Statut du gestionnaire mis à jour avec succès.'
        ]);
    }

    public function transactionsOrange(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = TransacOrange::query()
            ->join('users', 'transac_orange.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId)
            ->select('transac_orange.*', 'users.name as gestionnaire_name');

        if ($request->filled('gestionnaire')) {
            $query->where('transac_orange.user_id', $request->gestionnaire);
        }

        $transactions = $query->orderBy('transac_orange.date', 'desc')
            ->paginate(15)
            ->appends($request->query()); // Garde le filtre dans les liens de pagination

        $gestionnaires = User::where('role', 'gestionnaire')
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.transactions.orange', compact('transactions', 'gestionnaires'));
    }

    public function transactionsMtn(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = TransacMtn::query()
            ->join('users', 'transac_mtn.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId)
            ->select('transac_mtn.*', 'users.name as gestionnaire_name');

        if ($request->filled('gestionnaire')) {
            $query->where('transac_mtn.user_id', $request->gestionnaire);
        }

        $transactions = $query->orderBy('transac_mtn.date', 'desc')
            ->paginate(15)
            ->appends($request->query());

        $gestionnaires = User::where('role', 'gestionnaire')
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.transactions.mtn', compact('transactions', 'gestionnaires'));
    }
}
