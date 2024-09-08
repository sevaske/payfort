<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#check-status-request
 */
class CheckStatusServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'query_command' => PaymentCommand::CheckStatus->value,
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->defaultRules(),
            ...$this->credentialsRules(),
            'query_command' => [ // Alpha, Mandatory, Max 50
                'required',
                'in:CHECK_STATUS',
            ],
            'merchant_reference' => [ // Alphanumeric, Mandatory, Max 40, Special characters: - _ .
                'regex:/^[A-Za-z0-9\-_\.]+$/',
                'max:40',
                'required_without:fort_id',
            ],
            'fort_id' => [ // Numeric, Optional, Max 20
                'numeric',
                'digits_between:1,20',
                'required_without:merchant_reference',
            ],
            'return_third_party_response_codes' => [ // Alpha, Optional, Max 3
                'in:YES,NO', // Possible values
            ],
        ];
    }
}
