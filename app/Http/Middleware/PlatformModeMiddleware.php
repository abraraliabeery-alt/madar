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

        // Inside the facility panel, the facility's own category decides what is
        // allowed (a hybrid facility keeps both sections even if the public site
        // mode is narrower). Other contexts fall back to the site mode.
        $facility = $request->routeIs('facility.*') ? $request->user()?->mainFacility() : null;
        $facilityMode = $facility?->facilityCategory?->platform_mode
            ?? ($facility ? PlatformModeHelper::MODE_LIFECYCLE : null);

        $allowed = $facilityMode !== null
            ? in_array($facilityMode, [$capability, PlatformModeHelper::MODE_LIFECYCLE], true)
            : ($capability === 'real_estate' ? PlatformModeHelper::allowsRealEstate() : PlatformModeHelper::allowsContracting());

        if (!$allowed) {
            abort(404);
        }

        return $next($request);
    }
}
