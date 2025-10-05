<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Accès refusé. Seuls les super admins peuvent accéder à cette page.');
        }

        return $next($request);
    }
}

