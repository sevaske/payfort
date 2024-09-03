<?php

namespace Sevaske\Payfort\Enums;

enum PayfortEnvironment: string
{
    case Production = 'production';

    case Sandbox = 'sandbox';

    public function getApiUrl(): string
    {
        return match ($this->value) {
            self::Production->value => 'https://paymentservices.payfort.com/',
            self::Sandbox->value => 'https://sbpaymentservices.payfort.com/',
        };
    }
}
