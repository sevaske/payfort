<?php

return [
    // prod|sandbox
    'sandbox_mode' => env('PAYFORT_SANDBOX_MODE', true),
    // enables debug mode for logging detailed request/response information
    'debug_mode' => env('PAYFORT_DEBUG_MODE', false),
    // channel used for logging debug information
    'log_channel' => env('PAYFORT_LOG_CHANNEL', 'stack'),
    // language setting for the payfort api
    'language' => env('PAYFORT_LANGUAGE', 'en'), // en|ar

    // configuration for merchants
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
        // multiple merchants can be added here
    ],
];
