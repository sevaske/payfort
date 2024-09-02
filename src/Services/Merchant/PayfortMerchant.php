<?php

namespace Sevaske\Payfort\Services\Merchant;

use Sevaske\Payfort\Services\Http\PayfortHttpClient;
use Sevaske\Payfort\Services\Payment\PayfortPaymentApi;

class PayfortMerchant
{
    protected PayfortPaymentApi $api;

    public function __construct(
        protected PayfortHttpClient $http,
        protected PayfortCredentials $credentials
    ) {
        $this->api = new PayfortPaymentApi($http, $credentials);
    }

    public function getCredentials(): PayfortCredentials
    {
        return $this->credentials;
    }

    public function api(): PayfortPaymentApi
    {
        return $this->api;
    }
}
