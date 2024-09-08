<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Contracts\CredentialsContract;
use Sevaske\Payfort\Contracts\HasCredentials;
use Sevaske\Payfort\Services\ApiServices;

class Merchant implements HasCredentials
{
    private ?ApiServices $api = null;

    public function __construct(protected CredentialsContract $credentials) {}

    public function getCredentials(): CredentialsContract
    {
        return $this->credentials;
    }

    public function api(): ApiServices
    {
        if ($this->api === null) {
            $this->api = new ApiServices($this->credentials);
        }

        return $this->api;
    }
}
