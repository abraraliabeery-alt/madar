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
        $availableLocales = array_keys((array) config('locales.available', []));
        $rtlLocales = collect((array) config('locales.available', []))
            ->filter(fn($cfg) => ($cfg['direction'] ?? null) === 'rtl')
            ->keys()
            ->values()
            ->all();
        
        // If locale is set in session and is valid, set it
        if ($locale && in_array($locale, $availableLocales)) {
            App::setLocale($locale);
        }
        
        // Share current locale with all views
        view()->share('currentLocale', App::getLocale());
        view()->share('isRTL', in_array(App::getLocale(), $rtlLocales));

        return $next($request);
    }
}
