<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Companie;
use App\Models\Company; // Corriger l'utilisation de "Company" au lieu de "Companie"
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the post register redirect path.
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            return match ($role) {
                'super_admin' => '/superadmin/dashboard',
                'admin' => '/admin/dashboard',
                'gestionnaire' => '/gestionnaire/dashboard',
                default => '/home',
            };
        }
        return '/home';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email', 'unique:users,email'],
            'company_phone' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:5120'], // Max 5MB (5120 KB)
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Créer l'entreprise
        $company = Companie::create([
            'name' => $data['company_name'],
            'email' => $data['company_email'],
            'phone' => $data['company_phone'] ?? null,
        ]);

        // Gérer l'upload de l'avatar (optionnel)
        $avatarPath = 'assets/avatars/default.png'; // Chemin par défaut

        if (isset($data['avatar']) && $data['avatar']) {
            // Générer un nom unique pour l'image
            $avatarName = time() . '_' . uniqid() . '.' . $data['avatar']->getClientOriginalExtension();

            // Créer le dossier s'il n'existe pas
            $avatarDirectory = public_path('assets/avatars');
            if (!file_exists($avatarDirectory)) {
                mkdir($avatarDirectory, 0755, true);
            }

            // Déplacer l'image vers public/assets/avatars
            $data['avatar']->move($avatarDirectory, $avatarName);
            $avatarPath = 'assets/avatars/' . $avatarName;
        }

        // Créer l'utilisateur admin
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'status' => true,
            'company_id' => $company->id,
            'avatar' => $avatarPath,
        ]);

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->login($user); // Connecte l'utilisateur
        return redirect($this->redirectPath()); // Redirige selon le rôle
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Préparer les données avec le fichier avatar s'il existe
        $data = $request->all();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar');
        }

        $user = $this->create($data);

        event(new \Illuminate\Auth\Events\Registered($user));

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
