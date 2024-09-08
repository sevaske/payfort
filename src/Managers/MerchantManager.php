<?php

namespace Sevaske\Payfort\Managers;

use Illuminate\Support\Manager;
use Sevaske\Payfort\Credentials;
use Sevaske\Payfort\Exceptions\PayfortMerchantCredentialsException;
use Sevaske\Payfort\Merchant;
use Throwable;

class MerchantManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'default';
    }

    /**
     * @throws PayfortMerchantCredentialsException
     */
    public function createDriver($driver): Merchant
    {
        $config = $this->config['payfort']['merchants'][$driver] ?? [];

        if (! $config) {
            // not found
            throw new PayfortMerchantCredentialsException("Credentials for merchant [{$driver}] not found.");
        }

        try {
            $credentials = new Credentials(
                merchantIdentifier: $config['merchant_identifier'],
                accessToken: $config['access_code'],
                shaRequestPhrase: $config['sha_request_phrase'],
                shaResponsePhrase: $config['sha_response_phrase'],
                shaType: $config['sha_type'] ?? 'sha256',
            );
        } catch (Throwable $e) {
            // invalid
            throw new PayfortMerchantCredentialsException(
                message: "Credentials for merchant [{$driver}] are invalid. {$e->getMessage()}"
            );
        }

        return new Merchant($credentials);
    }
}
