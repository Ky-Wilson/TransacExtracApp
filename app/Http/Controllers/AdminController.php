<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function dashboard()
    {
        return view('admin.dashboard');
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
}