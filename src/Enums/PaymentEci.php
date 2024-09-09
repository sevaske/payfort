<?php

namespace Sevaske\Payfort\Enums;

enum PaymentEci: string
{
    case Moto = 'MOTO';

    case Recurring = 'RECURRING';

    case Ecommerce = 'ECOMMERCE';
}
