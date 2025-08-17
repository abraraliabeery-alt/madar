<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * Switch language
     */
    public function switch(Request $request, $language)
    {
        // Validate language
        if (!$this->languageService->setLanguage($language)) {
            abort(400, 'Invalid language');
        }

        // Get the previous URL and redirect back
        $previousUrl = $request->header('referer') ?: url('/');
        
        return Redirect::to($previousUrl);
    }

    /**
     * Get current language info
     */
    public function info()
    {
        return response()->json([
            'current' => $this->languageService->getCurrentLanguage(),
            'data' => $this->languageService->getCurrentLanguageData(),
            'isRTL' => $this->languageService->isRTL(),
            'available' => $this->languageService->getAvailableLanguages(),
        ]);
    }
}
