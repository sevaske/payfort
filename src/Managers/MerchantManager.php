<?php

namespace Sevaske\Payfort\Managers;

use Illuminate\Support\Manager;
use Sevaske\Payfort\Config;
use Sevaske\Payfort\Merchant;
use Sevaske\PayfortApi\Credential;
use Sevaske\PayfortApi\Exceptions\PayfortException;
use Throwable;

class MerchantManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'default';
    }

    /**
     * @throws PayfortException
     */
    public function createDriver($driver): Merchant
    {
        $config = $this->config['payfort']['merchants'][$driver] ?? [];

        if (! $config) {
            // not found
            throw new PayfortException("Credential for merchant [{$driver}] not found.");
        }

        try {
            $credential = new Credential(
                merchantIdentifier: $config['merchant_identifier'],
                accessCode: $config['access_code'],
                shaRequestPhrase: $config['sha_request_phrase'],
                shaResponsePhrase: $config['sha_response_phrase'],
                shaType: $config['sha_type'] ?: 'sha256',
            );
        } catch (Throwable $e) {
            // invalid
            throw new PayfortException(
                message: "Credential for merchant [{$driver}] are invalid. {$e->getMessage()}"
            );
        }

        return new Merchant(
            $driver,
            Config::isSandboxMode() ? 'sandbox' : 'production',
            app('payfort-http-client'),
            $credential
        );
    }
}
