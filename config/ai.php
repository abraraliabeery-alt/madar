<?php

return [
    'enabled' => env('AI_LAND_STUDY_ENABLED', false),

    'llm' => [
        'provider' => env('AI_LLM_PROVIDER', 'openai'), // openai|anthropic|azure
        'model' => env('AI_LLM_MODEL', 'gpt-4o-mini'),
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL'),
    ],

    'images' => [
        'provider' => env('AI_IMG_PROVIDER', 'openai'), // openai|stability
        'api_key' => env('STABILITY_API_KEY'),
        'openai_api_key' => env('OPENAI_API_KEY'),
        'size' => env('AI_IMG_SIZE', '1024x1024'),
    ],

    'cost' => [
        'max_per_request_usd' => env('AI_MAX_COST_PER_REQUEST', 2.0),
        'timeout_seconds' => env('AI_TIMEOUT_SECONDS', 45),
    ],
];
