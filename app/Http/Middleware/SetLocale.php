<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
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
        // Get locale from session
        $locale = Session::get('locale');
        
        // If locale is set in session and is valid, set it
        if ($locale && in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
        }
        
        // Share current locale with all views
        view()->share('currentLocale', App::getLocale());
        view()->share('isRTL', App::getLocale() === 'ar');

        return $next($request);
    }
}
