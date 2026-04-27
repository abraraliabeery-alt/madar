<?php

return [
    // Default chat provider used by the investment chat UI
    // Switched to OpenAI so we only need OPENAI_* configuration by default
    'default' => env('AI_CHAT_PROVIDER', 'openai'),

    'providers' => [
        'openai' => [
            'api_key'  => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'model'    => env('OPENAI_MODEL', 'gpt-4o-mini'),
        ],

        'gemini' => [
            'api_key'  => env('GEMINI_API_KEY'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            'model'    => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        ],

        'anthropic' => [
            'api_key'  => env('ANTHROPIC_API_KEY'),
            'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com/v1'),
            'model'    => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20240620'),
        ],
    ],
];
