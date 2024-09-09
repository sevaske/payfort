<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#void-authorization-operation-request
 */
class VoidAuthorizationServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'command' => PaymentCommand::VoidAuthorization->value,
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->defaultRules(),
            ...$this->credentialsRules(),
            'command' => [ // Alpha, Mandatory, Max 20, expected value: CAPTURE
                'required',
                'in:VOID_AUTHORIZATION',
            ],
            'merchant_reference' => [ // Alphanumeric with special chars '- _ .', Mandatory, Max 40
                'regex:/^[A-Za-z0-9_\.\-]+$/',
                'max:40',
                'required_without:fort_id',
            ],
            'fort_id' => [ // Numeric, Mandatory if merchant_identifier is missing, Max 20
                'numeric',
                'digits_between:1,20',
                'required_without:merchant_reference',
            ],
            'order_description' => [ // Alphanumeric with special chars ' / . _ - # : $, Optional, Max 150
                'regex:/^[A-Za-z0-9\/._#:$ \-]+$/',
                'max:150',
            ],
        ];
    }
}
