<?php

namespace Sevaske\Payfort\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Sevaske\Payfort\Facades\Payfort;
use Sevaske\Payfort\Merchant;
use Sevaske\PayfortApi\Enums\PayfortStatusEnum;
use Sevaske\PayfortApi\Exceptions\PayfortRequestException;
use Sevaske\PayfortApi\Exceptions\PayfortSignatureException;
use Sevaske\PayfortApi\Signature;

class PayfortWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws PayfortSignatureException
     * @throws PayfortRequestException
     */
    public function handle(Request $request, Closure $next)
    {
        $merchantName = $request->route('merchant', 'default');
        $merchant = Payfort::merchant($merchantName);
        $payload = $this->getPayload($request);

        // a request with the "Invalid Request" status does not include a signature
        if (($payload['status'] ?? null) === PayfortStatusEnum::InvalidRequest->value) {
            throw (new PayfortRequestException('Payfort webhook. Invalid request.', $payload))
                ->withContext([
                    'uri' => $request->getUri(),
                    'merchant' => $merchant->name(),
                ]);
        }

        $this->verifySignature($payload, $merchant);

        return $next($request);
    }

    /**
     * @throws PayfortSignatureException
     */
    protected function verifySignature(array $request, Merchant $merchant): void
    {
        $payload = Arr::except($request, ['signature']);
        $calculatedSignature = (new Signature(
            $merchant->credential()->shaResponsePhrase(),
            $merchant->credential()->shaType(),
        ))->calculate($payload);

        if ($request['signature'] !== $calculatedSignature) {
            throw (new PayfortSignatureException(
                'Signature is missing.',
                $payload,
                $calculatedSignature,
                $request['signature'],
                $merchant->credential()->shaResponsePhrase(),
                $merchant->credential()->shaType(),
            ));
        }
    }

    protected function getPayload(Request $request): array
    {
        $rawContent = (string) $request->getContent();

        if ($request->isJson()) {
            return (array) json_decode($rawContent, true);
        }

        parse_str($rawContent, $payload);

        return $payload;
    }
}
