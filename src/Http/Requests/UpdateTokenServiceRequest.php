<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#update-token-service
 */
class UpdateTokenServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'service_command' => PaymentCommand::UpdateToken->value,
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->defaultRules(),
            ...$this->credentialsRules(),
            'service_command' => [ // Alpha, Mandatory, Max: 20
                'required',
                'in:UPDATE_TOKEN',
            ],
            'merchant_reference' => [
                'required',
                'regex:/^[A-Za-z0-9_\.\-]+$/',
                'max:40',
            ],
            'token_name' => [ // Alphanumeric, Optional, Max: 100, Special characters: . @ - _
                'required',
                'regex:/^[A-Za-z0-9.@\-_]+$/',
                'max:100',
            ],
            'card_holder_name' => [ // Alpha, Optional, Max: 50, Special characters: ' - .
                'regex:/^[A-Za-z\s\'\-\.]+$/',
                'max:50',
            ],
            'currency' => [ // Alpha, Optional, Max: 3
                'alpha',
                'max:3', // ISO code 3
            ],
            'token_status' => [
                'in:ACTIVE,INACTIVE',
            ],
            'new_token_name' => [
                'regex:/^[A-Za-z0-9.@\-_]+$/',
                'max:100',
            ],
        ];
    }
}
