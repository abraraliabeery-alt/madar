<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | This configuration defines the available locales for the application.
    | Each locale should have a name, flag, and direction (RTL/LTR).
    |
    */

    'available' => [
        'ar' => [
            'name' => 'العربية',
            'flag' => '🇸🇦',
            'direction' => 'rtl',
            'native_name' => 'العربية'
        ],
        'en' => [
            'name' => 'English',
            'flag' => '🇺🇸',
            'direction' => 'ltr',
            'native_name' => 'English'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | This is the default locale that will be used when no locale is specified.
    |
    */

    'default' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | This is the fallback locale that will be used when a translation is missing.
    |
    */

    'fallback' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Locale Names for Display
    |--------------------------------------------------------------------------
    |
    | These are the names that will be displayed in the admin panel.
    |
    */

    'names' => [
        'ar' => 'العربية',
        'en' => 'English',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Flags for Display
    |--------------------------------------------------------------------------
    |
    | These are the flag emojis that will be displayed in the admin panel.
    |
    */

    'flags' => [
        'ar' => '🇸🇦',
        'en' => '🇺🇸',
    ],
];
