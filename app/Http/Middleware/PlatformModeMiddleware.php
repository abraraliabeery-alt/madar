<?php

namespace App\Http\Middleware;

use App\Helpers\PlatformModeHelper;
use Closure;
use Illuminate\Http\Request;

class PlatformModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: ->middleware('platform.mode:real_estate') or ->middleware('platform.mode:contracting')
     */
    public function handle(Request $request, Closure $next, string $capability)
    {
        $capability = trim($capability);

        if ($capability === 'real_estate' && !PlatformModeHelper::allowsRealEstate()) {
            abort(404);
        }

        if ($capability === 'contracting' && !PlatformModeHelper::allowsContracting()) {
            abort(404);
        }

        return $next($request);
    }
}
