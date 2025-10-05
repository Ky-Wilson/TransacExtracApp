<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'gestionnaire') {
            return redirect()->back()->with('error', 'Accès refusé. Seuls les gestionnaires peuvent accéder à cette page.');
        }

        return $next($request);
    }
}

