<?php

namespace Sevaske\Payfort\Services\Merchant;

use Sevaske\Payfort\Exceptions\PayfortMerchantCredentialsException;
use Sevaske\Payfort\Services\Http\PayfortHttpClient;
use Throwable;

class PayfortMerchantManager extends \Illuminate\Support\Manager
{
    public function getDefaultDriver(): string
    {
        return 'default';
    }

    /**
     * @throws PayfortMerchantCredentialsException
     */
    public function createDriver($driver): PayfortMerchant
    {
        $config = $this->config['payfort']['merchants'][$driver] ?? [];

        if (! $config) {
            // not found
            throw new PayfortMerchantCredentialsException("Credentials for merchant [{$driver}] not found.");
        }

        try {
            $credentials = new PayfortCredentials(
                merchantIdentifier: $config['merchant_identifier'],
                accessToken: $config['access_code'],
                shaRequestPhrase: $config['sha_request_phrase'],
                shaResponsePhrase: $config['sha_response_phrase'],
                shaType: $config['sha_type'] ?? 'sha256',
            );
        } catch (Throwable) {
            // invalid
            throw new PayfortMerchantCredentialsException("Credentials for merchant [{$driver}] are invalid.");
        }

        return new PayfortMerchant(app(PayfortHttpClient::class), $credentials);
    }
}
