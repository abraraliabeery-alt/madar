<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class LanguageService
{
    /**
     * Available languages
     */
    protected $availableLanguages = [
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇺🇸',
            'rtl' => false,
        ],
        'ar' => [
            'name' => 'Arabic',
            'native' => 'العربية',
            'flag' => '🇸🇦',
            'rtl' => true,
        ],
        'ur' => [
            'name' => 'Urdu',
            'native' => 'اردو',
            'flag' => '🇵🇰',
            'rtl' => true,
        ],
        'zh' => [
            'name' => 'Chinese',
            'native' => '中文',
            'flag' => '🇨🇳',
            'rtl' => false,
        ],
    ];

    /**
     * Default language
     */
    protected $defaultLanguage = 'en';

    /**
     * Get available languages
     */
    public function getAvailableLanguages()
    {
        return $this->availableLanguages;
    }

    /**
     * Get current language
     */
    public function getCurrentLanguage()
    {
        return App::getLocale();
    }

    /**
     * Get current language data
     */
    public function getCurrentLanguageData()
    {
        $current = $this->getCurrentLanguage();
        return $this->availableLanguages[$current] ?? $this->availableLanguages[$this->defaultLanguage];
    }

    /**
     * Check if current language is RTL
     */
    public function isRTL()
    {
        $current = $this->getCurrentLanguage();
        return $this->availableLanguages[$current]['rtl'] ?? false;
    }

    /**
     * Set language
     */
    public function setLanguage($language)
    {
        if (array_key_exists($language, $this->availableLanguages)) {
            App::setLocale($language);
            Session::put('locale', $language);
            Cache::put('locale', $language, now()->addYear());
            return true;
        }
        return false;
    }

    /**
     * Initialize language from request/session
     */
    public function initializeLanguage(Request $request)
    {
        // Check if language is set in URL
        $locale = $request->segment(1);
        
        if (array_key_exists($locale, $this->availableLanguages)) {
            $this->setLanguage($locale);
            return $locale;
        }

        // Check session
        $locale = Session::get('locale');
        if ($locale && array_key_exists($locale, $this->availableLanguages)) {
            $this->setLanguage($locale);
            return $locale;
        }

        // Check cache
        $locale = Cache::get('locale');
        if ($locale && array_key_exists($locale, $this->availableLanguages)) {
            $this->setLanguage($locale);
            return $locale;
        }

        // Use default language
        $this->setLanguage($this->defaultLanguage);
        return $this->defaultLanguage;
    }

    /**
     * Get language switcher data
     */
    public function getLanguageSwitcherData()
    {
        $current = $this->getCurrentLanguage();
        $switcher = [];

        foreach ($this->availableLanguages as $code => $language) {
            $switcher[] = [
                'code' => $code,
                'name' => $language['name'],
                'native' => $language['native'],
                'flag' => $language['flag'],
                'rtl' => $language['rtl'],
                'current' => $code === $current,
                'url' => $this->getLanguageUrl($code),
            ];
        }

        return $switcher;
    }

    /**
     * Get language URL
     */
    protected function getLanguageUrl($language)
    {
        $currentUrl = request()->url();
        $currentPath = request()->path();
        
        // Remove current language from path if exists
        $currentLocale = $this->getCurrentLanguage();
        if (str_starts_with($currentPath, $currentLocale)) {
            $currentPath = substr($currentPath, strlen($currentLocale) + 1);
        }
        
        // Build new URL
        if ($language === $this->defaultLanguage) {
            return url($currentPath);
        }
        
        return url($language . '/' . $currentPath);
    }

    /**
     * Get localized route
     */
    public function getLocalizedRoute($name, $parameters = [], $absolute = true)
    {
        $current = $this->getCurrentLanguage();
        
        if ($current === $this->defaultLanguage) {
            return route($name, $parameters, $absolute);
        }
        
        // Add locale prefix to route name
        $localizedName = $current . '.' . $name;
        
        try {
            return route($localizedName, $parameters, $absolute);
        } catch (\Exception $e) {
            // Fallback to original route if localized route doesn't exist
            return route($name, $parameters, $absolute);
        }
    }

    /**
     * Get localized URL
     */
    public function getLocalizedUrl($path = '', $parameters = [], $secure = null)
    {
        $current = $this->getCurrentLanguage();
        
        if ($current === $this->defaultLanguage) {
            return url($path, $parameters, $secure);
        }
        
        return url($current . '/' . $path, $parameters, $secure);
    }

    /**
     * Get translation with fallback
     */
    public function trans($key, $replace = [], $locale = null)
    {
        $locale = $locale ?: $this->getCurrentLanguage();
        
        // Try to get translation in current locale
        $translation = __($key, $replace, $locale);
        
        // If translation is the same as key, try fallback locale
        if ($translation === $key && $locale !== $this->defaultLanguage) {
            $translation = __($key, $replace, $this->defaultLanguage);
        }
        
        return $translation;
    }

    /**
     * Get choice translation with fallback
     */
    public function transChoice($key, $number, $replace = [], $locale = null)
    {
        $locale = $locale ?: $this->getCurrentLanguage();
        
        // Try to get translation in current locale
        $translation = trans_choice($key, $number, $replace, $locale);
        
        // If translation is the same as key, try fallback locale
        if ($translation === $key && $locale !== $this->defaultLanguage) {
            $translation = trans_choice($key, $number, $replace, $this->defaultLanguage);
        }
        
        return $translation;
    }
}
