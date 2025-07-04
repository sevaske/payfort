<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Managers\MerchantManager;

class Payfort
{
    public function merchants(): MerchantManager
    {
        return app(MerchantManager::class);
    }

    public function merchant(string $name = 'default'): Merchant
    {
        return $this->merchants()->driver($name);
    }
}
