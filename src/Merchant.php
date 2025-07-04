<?php

namespace Sevaske\Payfort;

use Psr\Http\Client\ClientInterface;
use Sevaske\PayfortApi\Enums\PayfortEnvironmentEnum;
use Sevaske\PayfortApi\Interfaces\CredentialInterface;
use Sevaske\PayfortApi\Merchant as PayfortMerchant;

class Merchant extends PayfortMerchant
{
    /**
     * Initialize the API request with an HTTP client and credentials.
     *
     * @param  PayfortEnvironmentEnum|string  $environment  The environment to make requests (production|sandbox).
     * @param  ClientInterface  $httpClient  The HTTP client for sending requests.
     * @param  CredentialInterface  $credential  The credential instance for authentication and signing requests.
     */
    public function __construct(
        protected string $name,
        PayfortEnvironmentEnum|string $environment,
        ClientInterface $httpClient,
        CredentialInterface $credential,
    ) {
        parent::__construct($environment, $httpClient, $credential);
    }

    public function name(): string
    {
        return $this->name;
    }
}
