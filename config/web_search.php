<?php

return [
    'provider' => env('WEB_SEARCH_PROVIDER', 'serpapi'),

    'serpapi' => [
        'api_key' => env('SERPAPI_KEY'),
        'base_url' => env('SERPAPI_BASE_URL', 'https://serpapi.com'),
        'engine' => env('SERPAPI_ENGINE', 'google'),
        'gl' => env('SERPAPI_GL', 'sa'),
        'hl' => env('SERPAPI_HL', 'ar'),
        'timeout_seconds' => env('WEB_SEARCH_TIMEOUT_SECONDS', 25),
        'results_limit' => env('WEB_SEARCH_RESULTS_LIMIT', 10),
    ],

    'google_cse' => [
        'api_key' => env('GOOGLE_CSE_KEY'),
        'cx' => env('GOOGLE_CSE_CX'),
        'base_url' => env('GOOGLE_CSE_BASE_URL', 'https://www.googleapis.com'),
        'timeout_seconds' => env('WEB_SEARCH_TIMEOUT_SECONDS', 25),
        'results_limit' => env('WEB_SEARCH_RESULTS_LIMIT', 10),
        'gl' => env('GOOGLE_CSE_GL', 'sa'),
        'hl' => env('GOOGLE_CSE_HL', 'ar'),
    ],

    // Allowed sources (domains) - keep this conservative
    'sources' => [
        'haraj' => [
            'label' => 'حراج',
            'domain' => 'haraj.com.sa',
        ],
        'bayut' => [
            'label' => 'بيوت',
            'domain' => 'bayut.sa',
        ],
        // Add exact domains here when confirmed
        'aqar' => [
            'label' => 'عقار',
            'domain' => 'aqar.fm',
        ],
    ],
];
