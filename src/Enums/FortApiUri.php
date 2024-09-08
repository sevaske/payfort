<?php

namespace Sevaske\Payfort\Enums;

enum FortApiUri: string
{
    case PaymentApi = '/FortAPI/paymentApi';

    case PaymentPage = '/FortAPI/paymentPage';
}
