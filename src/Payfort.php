<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Services\Http\PayfortHttpClient;
use Sevaske\Payfort\Services\Merchant\PayfortMerchant;
use Sevaske\Payfort\Services\Merchant\PayfortMerchantManager;

class Payfort
{
    public function http(): PayfortHttpClient
    {
        return app(PayfortHttpClient::class);
    }

    public function merchantManager(): PayfortMerchantManager
    {
        return app(PayfortMerchantManager::class);
    }

    public function merchant(string $name = 'default'): PayfortMerchant
    {
        return $this->merchantManager()->driver($name);
    }
}
