<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the post-login redirect path.
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
                default => '/login',
            };
        }
        return '/login';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->status) {
            Auth::logout();

            // Charger SweetAlert2 via un script inline (assurez-vous qu'il est inclus dans le layout)
            $message = $user->role === 'admin' 
                ? "Votre compte est inactif. Veuillez contacter le super admin pour plus d'informations."
                : "Votre compte est inactif. Veuillez contacter votre admin pour plus d'informations.";

            return redirect()->back()->with('error', $message);
        }

        return redirect()->intended($this->redirectPath());
    }
}