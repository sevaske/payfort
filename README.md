# Laravel package for payfort

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sevaske/payfort.svg?style=flat-square)](https://packagist.org/packages/sevaske/payfort)
[![Total Downloads](https://img.shields.io/packagist/dt/sevaske/payfort.svg?style=flat-square)](https://packagist.org/packages/sevaske/payfort)

This Laravel plugin lets you work with the Payfort Payment API and manage multiple merchants easily.

## Requirements

- PHP 8.1+
- Laravel 10+

## Installation

You can install the package via composer:

```bash
composer require sevaske/payfort
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="payfort-config"
```

This is the contents of the published config file (payfort.php):

```php
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
```

Add the following lines to your `.env` file and set values:

```dotenv
PAYFORT_SANDBOX_MODE=true
PAYFORT_DEBUG_MODE=true
# merchant: default
PAYFORT_MERCHANT_IDENTIFIER=
PAYFORT_ACCESS_CODE=
PAYFORT_SHA_REQUEST_PHRASE=
PAYFORT_SHA_RESPONSE_PHRASE=
# merchant: apple
PAYFORT_APPLE_MERCHANT_IDENTIFIER=
PAYFORT_APPLE_ACCESS_CODE=
PAYFORT_APPLE_SHA_REQUEST_PHRASE=
PAYFORT_APPLE_SHA_RESPONSE_PHRASE=
```

## Usage

```php
use \Sevaske\Payfort\Exceptions\PayfortMerchantCredentialsException;
use \Sevaske\Payfort\Exceptions\PayfortRequestException;
use \Sevaske\Payfort\Exceptions\PayfortResponseException;
use \Sevaske\Payfort\Http\PayfortSignature;
use \Sevaske\Payfort\Http\PayfortResponse;
use \Sevaske\Payfort\Payfort;

try {
    $response = Payfort::merchant('default')
        ->api()
        ->checkStatus(merchantReference: 'ORDER-123456') // PayfortResponse
        ->getData(); // array
} catch (PayfortMerchantCredentialsException $exception) {
    // handle
} catch (PayfortRequestException $exception) {
    // handle
} catch (PayfortResponseException $exception) {
    // handle
}

// api calls
// merchant: apple
 Payfort::merchant('apple')->api()->capture();
 // merchant: default
 Payfort::merchant()->api()->capture(); 
 Payfort::merchant()->api()->checkStatus();
 Payfort::merchant()->api()->createToken();
 Payfort::merchant()->api()->recurring();
 Payfort::merchant()->api()->refund();
 Payfort::merchant()->api()->updateToken();
 Payfort::merchant()->api()->voidAuthorization();

// custom request using merchant credentials
Payfort::merchant()->api()->request(options: ['json' => [
    'query_command' => 'CHECK_STATUS',
    'merchant_reference' => '5000900',
]]); // PayfortResponse

// custom request
Payfort::http()->request('POST', '/FortAPI/paymentApi', []); // PayfortResponse

// signature
$payload = []; // request data
$signature = (new PayfortSignature(shaPhrase: '', shaType: 'sha256'))
    ->calculateSignature($payload);

```

### Multiple merchants

Add new merchants in config/payfort.php.

### Debug mode

Enables debug mode for logging detailed request/response information. You can set the "log_channel".

## Beta Version

**Note**: This plugin is currently in beta. This means that while it is functional and ready for use, it may still have some bugs or incomplete features. We are actively working on improvements and welcome feedback to help us enhance the plugin.

### What to Expect

- **Feature Set**: Some features might be incomplete or subject to change based on user feedback and further development.
- **Stability**: Although we strive for stability, you may encounter issues or bugs. Please report any problems you find.
- **Support**: We provide basic support for beta users, but responses might be slower compared to stable releases.

Thank you for trying out our beta version and helping us make it better!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
