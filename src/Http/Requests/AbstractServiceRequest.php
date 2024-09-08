<?php

namespace Sevaske\Payfort\Http\Requests;

use Sevaske\Payfort\Contracts\ServiceRequestContract;
use Sevaske\Payfort\Credentials;
use Sevaske\Payfort\Enums\FortApiUri;

abstract class AbstractServiceRequest implements ServiceRequestContract
{
    public function __construct(protected ?Credentials $credentials = null) {}

    public function getPreparedRequestData(): array
    {
        return [];
    }

    public function getUri(): string
    {
        return FortApiUri::PaymentApi->value;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    protected function defaultRules(): array
    {
        return [
            'language' => [// Alpha, Mandatory, Max 2, expected values: en/ar
                'required',
                'in:en,ar',
            ],
        ];
    }

    protected function credentialsRules(): array
    {
        return [
            'access_code' => [ // Alphanumeric, Mandatory, Max 20
                'required',
                'alpha_num',
                'max:20',
            ],
            'merchant_identifier' => [ // Alphanumeric, Mandatory if fort_id is missing, Max 20
                'required',
                'alpha_num',
                'max:20',
            ],
            'signature' => [ // Alphanumeric, Mandatory, Max 200
                'required',
                'alpha_num',
                'max:200',
            ],
        ];
    }
}
