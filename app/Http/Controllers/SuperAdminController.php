<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('superadmin.dashboard');
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
}
