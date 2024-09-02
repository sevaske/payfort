<?php

namespace Sevaske\Payfort\Enums;

enum IntegrationType: string
{
    case Redirect = 'redirect';

    case Standard = 'standard';

    case Custom = 'custom';

    case Trusted = 'trusted';
}
