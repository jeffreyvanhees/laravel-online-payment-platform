<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Online Payment Platform API credentials and environment.
    | Set OPP_API_KEY and OPP_SANDBOX in your .env file.
    |
    */

    'api_key' => env('OPP_API_KEY'),
    
    'sandbox' => env('OPP_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for API responses. This can help reduce API calls
    | and improve performance for frequently accessed data.
    |
    */

    'cache' => [
        'enabled' => env('OPP_CACHE_ENABLED', false),
        'ttl' => env('OPP_CACHE_TTL', 300), // 5 minutes in seconds
        'store' => env('OPP_CACHE_STORE', null), // Use default cache store if null
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Configuration
    |--------------------------------------------------------------------------
    |
    | Configure HTTP client settings including timeouts and retry behavior.
    |
    */

    'http' => [
        'timeout' => env('OPP_HTTP_TIMEOUT', 30),
        'connect_timeout' => env('OPP_HTTP_CONNECT_TIMEOUT', 10),
        'retry' => [
            'max_attempts' => env('OPP_HTTP_RETRY_ATTEMPTS', 3),
            'delay' => env('OPP_HTTP_RETRY_DELAY', 1000), // milliseconds
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure webhook handling for receiving notifications from OPP.
    |
    */

    'webhooks' => [
        'secret' => env('OPP_WEBHOOK_SECRET'),
        'verify_signature' => env('OPP_WEBHOOK_VERIFY_SIGNATURE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for API requests and responses. Useful for debugging.
    |
    */

    'logging' => [
        'enabled' => env('OPP_LOGGING_ENABLED', false),
        'channel' => env('OPP_LOGGING_CHANNEL', 'default'),
        'level' => env('OPP_LOGGING_LEVEL', 'debug'),
    ],
];