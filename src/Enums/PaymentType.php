<?php

namespace Sevaske\Payfort\Enums;

enum PaymentType: string
{
    case CreditCard = 'credit_card';

    case ApplePay = 'apple_pay';

    case Instalments = 'installments';
}
