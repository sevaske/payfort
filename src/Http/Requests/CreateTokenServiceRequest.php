<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#create-new-token-service
 */
class CreateTokenServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'service_command' => PaymentCommand::CreateToken->value,
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->defaultRules(),
            ...$this->credentialsRules(),
            'service_command' => [ // Alpha, Mandatory, Max: 20
                'required',
                'in:CREATE_TOKEN',
            ],
            'merchant_reference' => [
                'required',
                'string',
                'max:40',
            ],
            'card_number' => [ // Numeric, Mandatory, Max: 19
                'required',
                'numeric',
                'digits_between:15,19', // MEEZA: 19 digits, AMEX: 15 digits, others: 16 digits
            ],
            'expiry_date' => [ // Numeric, Mandatory, Max: 4
                'required',
                'numeric',
                'digits:4', // Assuming expiry_date should be exactly 4 digits
            ],
            'return_url' => [ // Alphanumeric, Mandatory, Max: 400, Special characters: $ ! = ? # & - _ / : .
                'required',
                'string',
                'max:400',
            ],
            'currency' => [ // Alpha, Optional, Max: 3
                'alpha',
                'max:3', // ISO code 3
            ],
            'token_name' => [ // Alphanumeric, Optional, Max: 100, Special characters: . @ - _
                'string',
                'max:100',
            ],
            'card_holder_name' => [ // Alpha, Optional, Max: 50, Special characters: ' - .
                'string',
                'max:50',
            ],
        ];
    }
}
