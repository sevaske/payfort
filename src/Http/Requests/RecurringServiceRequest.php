<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;
use Sevaske\Payfort\Enums\PaymentEci;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#recurring-request
 */
class RecurringServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'command' => PaymentCommand::Purchase->value,
            'eci' => PaymentEci::Recurring->value,
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
                'string',
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
                'string',
                'max:100',
            ],
            'payment_option' => [ // Alpha, Optional, Max: 10
                'in:MASTERCARD,VISA,AMEX',
            ],
            'order_description' => [ // Alphanumeric, Optional, Max: 150, Special characters: ' / . _ - # : $ Space
                'string',
                'max:150',
            ],
            'customer_name' => [ // Alpha, Optional, Max: 40, Special characters: _ \ / - . ' Space
                'string',
                'max:40',
            ],
            'merchant_extra' => [ // Alphanumeric, Optional, Max: 999, Special characters: . ; / _ - , ' @
                'string',
                'max:999',
            ],
            'merchant_extra1' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'string',
                'max:250',
            ],
            'merchant_extra2' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'string',
                'max:250',
            ],
            'merchant_extra3' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'string',
                'max:250',
            ],
            'merchant_extra4' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'string',
                'max:250',
            ],
            'merchant_extra5' => [ // Alphanumeric, Optional, Max: 250, Special characters: . ; / _ - , ' @
                'string',
                'max:250',
            ],
            'phone_number' => [ // Alphanumeric, Optional, Max: 19, Special characters: + - ( ) Space
                'string',
                'max:19',
            ],
            'settlement_reference' => [ // Alphanumeric, Optional, Max: 22
                'string',
                'max:22',
            ],
            'agreement_id' => [ // Alphanumeric, Optional, Max: 15, No special characters
                'alpha_num',
                'max:15',
            ],
        ];
    }
}
