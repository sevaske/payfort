<?php

namespace Sevaske\Payfort\Enums;

enum PaymentEci: string
{
    case PaymentEciMoto = 'MOTO';

    case PaymentEciRecurring = 'RECURRING';

    case PaymentEciEcommerce = 'ECOMMERCE';
}
