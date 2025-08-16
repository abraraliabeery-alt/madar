<?php

namespace App\Helpers;

use App\Services\LanguageService;

class LanguageHelper
{
    /**
     * Get current language
     */
    public static function getCurrentLanguage()
    {
        return app(LanguageService::class)->getCurrentLanguage();
    }

    /**
     * Check if current language is RTL
     */
    public static function isRTL()
    {
        return app(LanguageService::class)->isRTL();
    }

    /**
     * Get current language data
     */
    public static function getCurrentLanguageData()
    {
        return app(LanguageService::class)->getCurrentLanguageData();
    }

    /**
     * Get available languages
     */
    public static function getAvailableLanguages()
    {
        return app(LanguageService::class)->getAvailableLanguages();
    }

    /**
     * Get language switcher data
     */
    public static function getLanguageSwitcherData()
    {
        return app(LanguageService::class)->getLanguageSwitcherData();
    }

    /**
     * Get localized route
     */
    public static function getLocalizedRoute($name, $parameters = [], $absolute = true)
    {
        return app(LanguageService::class)->getLocalizedRoute($name, $parameters, $absolute);
    }

    /**
     * Get localized URL
     */
    public static function getLocalizedUrl($path = '', $parameters = [], $secure = null)
    {
        return app(LanguageService::class)->getLocalizedUrl($path, $parameters, $secure);
    }

    /**
     * Get translation with fallback
     */
    public static function trans($key, $replace = [], $locale = null)
    {
        return app(LanguageService::class)->trans($key, $replace, $locale);
    }

    /**
     * Get choice translation with fallback
     */
    public static function transChoice($key, $number, $replace = [], $locale = null)
    {
        return app(LanguageService::class)->transChoice($key, $number, $replace, $locale);
    }

    /**
     * Get direction attribute for HTML
     */
    public static function getDirection()
    {
        return self::isRTL() ? 'rtl' : 'ltr';
    }

    /**
     * Get text alignment class
     */
    public static function getTextAlign()
    {
        return self::isRTL() ? 'text-right' : 'text-left';
    }

    /**
     * Get float class
     */
    public static function getFloat($side = 'left')
    {
        if (self::isRTL()) {
            return $side === 'left' ? 'float-right' : 'float-left';
        }
        return $side === 'left' ? 'float-left' : 'float-right';
    }

    /**
     * Get margin class
     */
    public static function getMargin($side = 'left')
    {
        if (self::isRTL()) {
            return $side === 'left' ? 'me-auto' : 'ms-auto';
        }
        return $side === 'left' ? 'ms-auto' : 'me-auto';
    }

    /**
     * Get padding class
     */
    public static function getPadding($side = 'left')
    {
        if (self::isRTL()) {
            return $side === 'left' ? 'pe-3' : 'ps-3';
        }
        return $side === 'left' ? 'ps-3' : 'pe-3';
    }

    /**
     * Get border class
     */
    public static function getBorder($side = 'left')
    {
        if (self::isRTL()) {
            return $side === 'left' ? 'border-end' : 'border-start';
        }
        return $side === 'left' ? 'border-start' : 'border-end';
    }

    /**
     * Get position class
     */
    public static function getPosition($side = 'left')
    {
        if (self::isRTL()) {
            return $side === 'left' ? 'right-0' : 'left-0';
        }
        return $side === 'left' ? 'left-0' : 'right-0';
    }

    /**
     * Get transform class
     */
    public static function getTransform($direction = 'left')
    {
        if (self::isRTL()) {
            return $direction === 'left' ? 'translate-x-100' : '-translate-x-100';
        }
        return $direction === 'left' ? '-translate-x-100' : 'translate-x-100';
    }

    /**
     * Get flex direction class
     */
    public static function getFlexDirection()
    {
        return self::isRTL() ? 'flex-row-reverse' : 'flex-row';
    }

    /**
     * Get justify content class
     */
    public static function getJustifyContent($side = 'start')
    {
        if (self::isRTL()) {
            return $side === 'start' ? 'justify-content-end' : 'justify-content-start';
        }
        return $side === 'start' ? 'justify-content-start' : 'justify-content-end';
    }

    /**
     * Get align items class
     */
    public static function getAlignItems($side = 'start')
    {
        if (self::isRTL()) {
            return $side === 'start' ? 'align-items-end' : 'align-items-start';
        }
        return $side === 'start' ? 'align-items-start' : 'align-items-end';
    }

    /**
     * Get order class
     */
    public static function getOrder($position = 'first')
    {
        if (self::isRTL()) {
            return $position === 'first' ? 'order-last' : 'order-first';
        }
        return $position === 'first' ? 'order-first' : 'order-last';
    }

    /**
     * Get language name by code
     */
    public static function getLanguageName($code)
    {
        $languages = self::getAvailableLanguages();
        return $languages[$code]['name'] ?? $code;
    }

    /**
     * Get native language name by code
     */
    public static function getNativeLanguageName($code)
    {
        $languages = self::getAvailableLanguages();
        return $languages[$code]['native'] ?? $code;
    }

    /**
     * Get language flag by code
     */
    public static function getLanguageFlag($code)
    {
        $languages = self::getAvailableLanguages();
        return $languages[$code]['flag'] ?? '🌐';
    }

    /**
     * Check if language is RTL by code
     */
    public static function isLanguageRTL($code)
    {
        $languages = self::getAvailableLanguages();
        return $languages[$code]['rtl'] ?? false;
    }

    /**
     * Get language switcher HTML
     */
    public static function getLanguageSwitcherHTML($class = '', $showFlags = true, $showNames = true, $dropdown = true)
    {
        $switcher = self::getLanguageSwitcherData();
        $current = self::getCurrentLanguageData();
        
        if ($dropdown) {
            $html = '<div class="language-switcher dropdown ' . $class . '">';
            $html .= '<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
            if ($showFlags) {
                $html .= '<span class="flag">' . $current['flag'] . '</span>';
            }
            if ($showNames) {
                $html .= '<span class="language-name">' . $current['native'] . '</span>';
            }
            $html .= '</button>';
            $html .= '<ul class="dropdown-menu">';
            
            foreach ($switcher as $language) {
                $active = $language['current'] ? ' active' : '';
                $html .= '<li><a class="dropdown-item' . $active . '" href="' . $language['url'] . '" data-language="' . $language['code'] . '">';
                if ($showFlags) {
                    $html .= '<span class="flag me-2">' . $language['flag'] . '</span>';
                }
                if ($showNames) {
                    $html .= '<span class="language-name">' . $language['native'] . '</span>';
                }
                $html .= '</a></li>';
            }
            
            $html .= '</ul></div>';
        } else {
            $html = '<div class="language-switcher-inline ' . $class . '">';
            
            foreach ($switcher as $language) {
                $active = $language['current'] ? ' active' : '';
                $html .= '<a href="' . $language['url'] . '" class="language-link' . $active . '" data-language="' . $language['code'] . '">';
                if ($showFlags) {
                    $html .= '<span class="flag">' . $language['flag'] . '</span>';
                }
                if ($showNames) {
                    $html .= '<span class="language-name">' . $language['native'] . '</span>';
                }
                $html .= '</a>';
            }
            
            $html .= '</div>';
        }
        
        return $html;
    }
}
