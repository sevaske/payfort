<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Enums\PaymentCommand;

/**
 * https://paymentservices-reference.payfort.com/docs/api/build/index.html#capture-operation-request
 */
class CaptureServiceRequest extends AbstractServiceRequest
{
    public function getPreparedRequestData(): array
    {
        return [
            'command' => PaymentCommand::Capture->value,
        ];
    }

    public function rules(): array
    {
        return [
            ...$this->defaultRules(),
            ...$this->credentialsRules(),
            'command' => [ // Alpha, Mandatory, Max 20, expected value: CAPTURE
                'required',
                'in:CAPTURE',
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
            'merchant_reference' => [ // Alphanumeric with special chars '- _ .', Mandatory, Max 40
                'string',
                'max:40',
                'required_without:fort_id',
            ],
            'fort_id' => [ // Numeric, Mandatory if merchant_identifier is missing, Max 20
                'numeric',
                'digits_between:1,20',
                'required_without:merchant_reference',
            ],
            'order_description' => [ // Alphanumeric with special chars ' / . _ - # : $, Optional, Max 150
                'string',
                'max:150',
            ],
        ];
    }
}
