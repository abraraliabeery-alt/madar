<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Services\LanguageService;

class SetLocale
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Initialize language from request/session
        $this->languageService->initializeLanguage($request);

        // Share language data with all views
        view()->share('currentLanguage', $this->languageService->getCurrentLanguage());
        view()->share('currentLanguageData', $this->languageService->getCurrentLanguageData());
        view()->share('isRTL', $this->languageService->isRTL());
        view()->share('languageSwitcher', $this->languageService->getLanguageSwitcherData());

        return $next($request);
    }
}
