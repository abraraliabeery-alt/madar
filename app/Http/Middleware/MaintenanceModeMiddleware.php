<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class MaintenanceModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $isMaintenance = (string) Setting::getValue('maintenance_mode', '0') === '1';

        if (!$isMaintenance) {
            return $next($request);
        }

        $path = ltrim($request->path(), '/');

        $excludedPrefixes = [
            'admin',
            'maintenance',
            'language',
            'language-info',
            'login',
            'register',
            'logout',
        ];

        foreach ($excludedPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return $next($request);
            }
        }

        return response()->view('errors.maintenance', [], 503);
    }
}
