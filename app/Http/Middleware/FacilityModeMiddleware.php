<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\FacilityHelper;
use Illuminate\Http\Request;

class FacilityModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // في حالة المنشأة الواحدة، إعادة توجيه بعض routes
        if (FacilityHelper::isSingleMode()) {
            // إعادة توجيه /facilities إلى المنشأة الوحيدة
            if ($request->is('facilities') && !$request->is('facilities/*')) {
                $facility = FacilityHelper::getSingleFacility();
                if ($facility) {
                    return redirect()->route('public.facilities.show', $facility);
                }
            }
        }

        return $next($request);
    }
}
