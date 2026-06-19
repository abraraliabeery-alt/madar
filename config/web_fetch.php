<?php

return [
    'timeout_seconds' => env('WEB_FETCH_TIMEOUT_SECONDS', 15),
    'max_urls' => env('WEB_FETCH_MAX_URLS', 20),
    'max_bytes' => env('WEB_FETCH_MAX_BYTES', 300000),

    'crawl' => [
        'cache_minutes' => env('WEB_CRAWL_CACHE_MINUTES', 15),
        'sitemap_urls_limit' => env('WEB_CRAWL_SITEMAP_URLS_LIMIT', 80),
        'fetch_items_per_source' => env('WEB_CRAWL_FETCH_ITEMS_PER_SOURCE', 10),
        'fetch_total_limit' => env('WEB_CRAWL_FETCH_TOTAL_LIMIT', 25),
    ],

    'sources' => [
        [
            'key' => 'haraj',
            'label' => 'حراج',
            'base_url' => env('WEB_CRAWL_HARAJ_BASE', 'https://haraj.com.sa'),
        ],
        [
            'key' => 'aqar',
            'label' => 'مشروع',
            'base_url' => env('WEB_CRAWL_AQAR_BASE', 'https://aqar.fm'),
        ],
        [
            'key' => 'bayut',
            'label' => 'بيوت',
            'base_url' => env('WEB_CRAWL_BAYUT_BASE', 'https://bayut.sa'),
        ],
    ],

    // Allowed domains only (exact match). Extend this list as needed.
    'allowed_domains' => [
        'haraj.com.sa',
        'www.haraj.com.sa',
        'bayut.sa',
        'www.bayut.sa',
        'aqar.fm',
        'www.aqar.fm',
    ],
];
