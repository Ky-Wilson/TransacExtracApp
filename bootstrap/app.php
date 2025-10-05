<?php

use App\Http\Middleware\CheckCompanyAdmin;
use App\Http\Middleware\CheckManager;
use App\Http\Middleware\CheckSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super_admin' => CheckSuperAdmin::class,
            'company_admin' => CheckCompanyAdmin::class,
            'manager' => CheckManager::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();