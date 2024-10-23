<?php

return [
    'sandbox_mode' => env('PAYFORT_SANDBOX_MODE', false),
    'debug_mode' => env('PAYFORT_DEBUG_MODE', false),
    'log_channel' => env('PAYFORT_LOG_CHANNEL', env('LOG_CHANNEL', 'stack')),
    'language' => env('PAYFORT_LANGUAGE', 'en'), // en|ar
    'enable_requests_validation' => env('PAYFORT_ENABLE_REQUESTS_VALIDATION', true),

    /*
    |--------------------------------------------------------------------------
    | Merchant Configuration
    |--------------------------------------------------------------------------
    |
    | This section allows you to configure multiple merchants for your application.
    | Each merchant can have its own unique settings, such as merchant identifier,
    | access code, and SHA phrases for secure transactions. You can easily
    | switch between different merchants using the Payfort::merchant({name}) method.
    | The default merchant is specified under 'default', while additional merchants
    | can be defined by adding their own unique keys.
    |
    */
    'merchants' => [
        'default' => [
            'merchant_identifier' => env('PAYFORT_MERCHANT_IDENTIFIER'),
            'access_code' => env('PAYFORT_ACCESS_CODE'),
            'sha_request_phrase' => env('PAYFORT_SHA_REQUEST_PHRASE'),
            'sha_response_phrase' => env('PAYFORT_SHA_RESPONSE_PHRASE'),
            'sha_type' => env('PAYFORT_SHA_TYPE', 'sha256'),
        ],
        'apple' => [
            'merchant_identifier' => env('PAYFORT_APPLE_MERCHANT_IDENTIFIER'),
            'access_code' => env('PAYFORT_APPLE_ACCESS_CODE'),
            'sha_request_phrase' => env('PAYFORT_APPLE_SHA_REQUEST_PHRASE'),
            'sha_response_phrase' => env('PAYFORT_APPLE_SHA_RESPONSE_PHRASE'),
            'sha_type' => env('PAYFORT_APPLE_SHA_TYPE', 'sha256'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure webhooks for your application. These settings
    | define where your server will receive responses from Amazon Payment
    | Services after a transaction is processed, as well as where offline
    | notifications will be received for any status updates regarding
    | transactions and orders. You can also specify the middleware that
    | should be applied to the webhook routes.
    |
    */
    'webhook' => [
        'feedback' => [
            'enabled' => env('PAYFORT_WEBHOOK_FEEDBACK_ENABLED', true),
            'uri' => env('PAYFORT_WEBHOOK_FEEDBACK_URI', '/payfort/webhook/feedback/{merchant?}'),
            'middlewares' => [
                \Sevaske\Payfort\Http\Middlewares\PayfortWebhookSignature::class,
            ],
        ],
        'notification' => [
            'enabled' => env('PAYFORT_WEBHOOK_NOTIFICATION_ENABLED', true),
            'uri' => env('PAYFORT_WEBHOOK_NOTIFICATION_URI', '/payfort/webhook/notification/{merchant?}'),
            'middlewares' => [
                \Sevaske\Payfort\Http\Middlewares\PayfortWebhookSignature::class,
            ],
        ],
    ],
];
