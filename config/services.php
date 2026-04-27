<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'browser_service' => [
        'base_url' => env('BROWSER_SERVICE_URL', 'http://127.0.0.1:4001'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'unifonic' => [
        'base_url' => env('UNIFONIC_BASE_URL', 'https://api.unifonic.com'),
        'api_key' => env('UNIFONIC_API_KEY'),
        'app_sid' => env('UNIFONIC_APP_SID'),
        'sender_id' => env('UNIFONIC_SENDER_ID'),
        'send_path' => env('UNIFONIC_SEND_PATH', '/rest/Messages/Send'),
        'timeout' => (int) env('UNIFONIC_TIMEOUT', 15),
    ],

];
