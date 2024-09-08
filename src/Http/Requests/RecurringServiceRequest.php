<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#recurring-request
 */
class RecurringServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'command' => PaymentCommand::Recurring->value,
            'eci' => 'RECURRING',
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->defaultRules(),
            ...$this->credentialsRules(),
            'command' => [ // Alpha, Mandatory, Max 20
                'required',
                'in:PURCHASE',
            ],
            'merchant_reference' => [ // Alphanumeric, Mandatory, Max 40, Special characters: - _ .
                'required',
                'regex:/^[A-Za-z0-9\-_\.]+$/',
                'max:40',
            ],
            'amount' => [ // Numeric, Mandatory, Max 10
                'required',
                'numeric',
                'max:9999999999',
            ],
            'currency' => [ // The currency of the transaction’s amount in ISO code 3
                'required',
                'alpha',
                'max:3',
            ],

            'customer_email' => [ // Alphanumeric, Mandatory, Max: 254, Special characters: _ - . @ +
                'required',
                'email',
                'max:254',
            ],
            'eci' => [ // Alpha, Mandatory, Max: 16
                'required',
                'in:RECURRING',
            ],
            'token_name' => [ // Alphanumeric, Mandatory, Max: 100, Special characters: _ - . @
                'required',
                'regex:/^[A-Za-z0-9_\-\.@]+$/',
                'max:100',
            ],
            'payment_option' => [ // Alpha, Optional, Max: 10
                'in:MASTERCARD,VISA,AMEX',
            ],
            'order_description' => [ // Alphanumeric, Optional, Max: 150, Special characters: ' / . _ - # : $ Space
                'regex:/^[A-Za-z0-9\'\/\.\-_#:\$ ]+$/',
                'max:150',
            ],
            'customer_name' => [ // Alpha, Optional, Max: 40, Special characters: _ \ / - . ' Space
                'regex:/^[A-Za-z_\\\/\-.\' ]+$/',
                'max:40',
            ],
            'merchant_extra' => [ // Alphanumeric, Optional, Max: 999, Special characters: . ; / _ - , ' @
                'regex:/^[A-Za-z0-9.;/_\-,\'@ ]+$/',
                'max:999',
            ],
            'merchant_extra1' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'regex:/^[A-Za-z0-9.;/_\-,\'@ ]+$/',
                'max:250',
            ],
            'merchant_extra2' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'regex:/^[A-Za-z0-9.;/_\-,\'@ ]+$/',
                'max:250',
            ],
            'merchant_extra3' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'regex:/^[A-Za-z0-9.;/_\-,\'@ ]+$/',
                'max:250',
            ],
            'merchant_extra4' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'regex:/^[A-Za-z0-9.;/_\-,\'@ ]+$/',
                'max:250',
            ],
            'merchant_extra5' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'regex:/^[A-Za-z0-9.;/_\-,\'@ ]+$/',
                'max:250',
            ],
            'phone_number' => [ // Alphanumeric, Optional, Max: 19, Special characters: + - ( ) Space
                'regex:/^[A-Za-z0-9+\-() ]+$/',
                'max:19',
            ],
            'settlement_reference' => [ // Alphanumeric, Optional, Max: 22
                'max:22',
            ],
            'agreement_id' => [ // Alphanumeric, Optional, Max: 15, No special characters
                'alpha_num',
                'max:15',
            ],
        ];
    }
}
