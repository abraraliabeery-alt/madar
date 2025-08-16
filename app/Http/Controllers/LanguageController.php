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

        // Get the previous URL
        $previousUrl = $request->header('referer') ?: url('/');
        
        // If we're switching to default language, remove language prefix
        if ($language === 'en') {
            $previousUrl = $this->removeLanguagePrefix($previousUrl);
        } else {
            // Add language prefix if not default
            $previousUrl = $this->addLanguagePrefix($previousUrl, $language);
        }

        return Redirect::to($previousUrl);
    }

    /**
     * Remove language prefix from URL
     */
    protected function removeLanguagePrefix($url)
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        
        // Remove language prefix if exists
        $segments = explode('/', trim($path, '/'));
        if (isset($segments[0]) && in_array($segments[0], ['en', 'ar'])) {
            array_shift($segments);
        }
        
        $newPath = '/' . implode('/', $segments);
        
        // Rebuild URL
        $parsed['path'] = $newPath;
        return $this->buildUrl($parsed);
    }

    /**
     * Add language prefix to URL
     */
    protected function addLanguagePrefix($url, $language)
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        
        // Add language prefix if not exists
        $segments = explode('/', trim($path, '/'));
        if (!isset($segments[0]) || !in_array($segments[0], ['en', 'ar'])) {
            array_unshift($segments, $language);
        }
        
        $newPath = '/' . implode('/', $segments);
        
        // Rebuild URL
        $parsed['path'] = $newPath;
        return $this->buildUrl($parsed);
    }

    /**
     * Build URL from parsed components
     */
    protected function buildUrl($parsed)
    {
        $scheme = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
        $host = $parsed['host'] ?? '';
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path = $parsed['path'] ?? '';
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
        
        return $scheme . $host . $port . $path . $query . $fragment;
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
