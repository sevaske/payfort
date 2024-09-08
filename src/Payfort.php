<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Http\PayfortHttpClient;
use Sevaske\Payfort\Managers\MerchantManager;

class Payfort
{
    public function http(): PayfortHttpClient
    {
        return app(PayfortHttpClient::class);
    }

    public function merchants(): MerchantManager
    {
        return app(MerchantManager::class);
    }

    public function merchant(string $name = 'default'): Merchant
    {
        return $this->merchants()->driver($name);
    }
}
