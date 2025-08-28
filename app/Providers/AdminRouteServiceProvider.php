<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load admin routes
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));
            
        // Load facility routes
        Route::middleware('web')
            ->group(base_path('routes/facility.php'));
            
        // Load client routes
        Route::middleware('web')
            ->group(base_path('routes/client.php'));
            
        // Load public routes
        Route::middleware('web')
            ->group(base_path('routes/public.php'));
    }
}
