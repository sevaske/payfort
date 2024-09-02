# Laravel package for payfort

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sevaske/payfort.svg?style=flat-square)](https://packagist.org/packages/sevaske/payfort)
[![Total Downloads](https://img.shields.io/packagist/dt/sevaske/payfort.svg?style=flat-square)](https://packagist.org/packages/sevaske/payfort)

This Laravel plugin lets you work with the Payfort Payment API and manage multiple merchants easily.

## Requirements

- PHP 8.1+
- Laravel 9+

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
            'merchant_identifier' => env('PAYFORT_MERCHANT_IDENTIFIER'),
            'access_code' => env('PAYFORT_APPLE_ACCESS_CODE'),
            'sha_request_phrase' => env('PAYFORT_APPLE_SHA_REQUEST_PHRASE'),
            'sha_response_phrase' => env('PAYFORT_APPLE_SHA_RESPONSE_PHRASE'),
            'sha_type' => env('PAYFORT_APPLE_SHA_TYPE', 'sha256'),
        ],
        // multiple merchants can be added here
    ],

    // payfort payment api urls for prod & sandbox
    'api_url' => env('PAYFORT_API_URL', 'https://paymentservices.payfort.com/'),
    'sandbox_api_url' => env('PAYFORT_SANDBOX_API_URL', 'https://sbpaymentservices.payfort.com/'),
];
```

Add the following lines to your `.env` file and set values:

```dotenv
PAYFORT_SANDBOX_MODE=true
PAYFORT_DEBUG_MODE=false
PAYFORT_LOG_CHANNEL=stack
PAYFORT_LANGUAGE=en
# default merchant
PAYFORT_MERCHANT_IDENTIFIER=
PAYFORT_ACCESS_CODE=
PAYFORT_SHA_REQUEST_PASSPHRASE=
PAYFORT_SHA_RESPONSE_PASSPHRASE=
PAYFORT_SHA_TYPE=sha256
# merchant "apple"
PAYFORT_APPLE_MERCHANT_IDENTIFIER
PAYFORT_APPLE_ACCESS_CODE=
PAYFORT_APPLE_SHA_REQUEST_PASSPHRASE=
PAYFORT_APPLE_SHA_RESPONSE_PASSPHRASE=
PAYFORT_APPLE_SHA_TYPE=sha256
```

## Usage

```php
use \Sevaske\Payfort\Facades\Payfort

try {
    // \Sevaske\Payfort\Services\Http\PayfortResponse
    $response = Payfort::merchant('default')->api()->checkStatus([
        'merchant_reference' => 12345,
    ]);
    
} catch (PayfortMerchantCredentialsException $exception) {
    // handle
} catch (PayfortRequestException $exception) {
    // handle
} catch (PayfortResponseException $exception) {
    // handle
}

// Also
 Payfort::merchant('default')->api()->authorization([]);
 Payfort::merchant('default')->api()->purchase([]);
 Payfort::merchant('default')->api()->tokenization([]);
 Payfort::merchant('default')->api()->capture([]);
 Payfort::merchant('default')->api()->voidAuthorization([]);
 Payfort::merchant('default')->api()->refund([]);
 Payfort::merchant('default')->api()->recurring([]);

// Custom command
Payfort::merchant('default')->api()->command('ANOTHER', []);

// custom request
Payfort::http()->request('POST', '/FortAPI/paymentApi', []);
// custom request with the access_code, signature and other parameters
Payfort::merchant('default')->api()->http()->request('POST', '/FortAPI/paymentApi', []);
```

## Testing

```bash
composer test
```

## Beta Version

**Note**: This plugin is currently in beta. This means that while it is functional and ready for use, it may still have some bugs or incomplete features. We are actively working on improvements and welcome feedback to help us enhance the plugin.

### What to Expect

- **Feature Set**: Some features might be incomplete or subject to change based on user feedback and further development.
- **Stability**: Although we strive for stability, you may encounter issues or bugs. Please report any problems you find.
- **Support**: We provide basic support for beta users, but responses might be slower compared to stable releases.

Thank you for trying out our beta version and helping us make it better!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
