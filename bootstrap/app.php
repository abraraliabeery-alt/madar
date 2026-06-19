<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'facility.mode' => \App\Http\Middleware\FacilityModeMiddleware::class,
            'facility.access' => \App\Http\Middleware\FacilityModeMiddleware::class,
        ]);
        
        // Add SetLocale middleware to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\MaintenanceModeMiddleware::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withProviders([
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
