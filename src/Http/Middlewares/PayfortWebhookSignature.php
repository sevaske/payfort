<?php

namespace Sevaske\Payfort\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Sevaske\Payfort\Enums\PaymentApiResponseStatus;
use Sevaske\Payfort\Exceptions\PayfortInvalidRequestException;
use Sevaske\Payfort\Exceptions\PayfortSignatureException;
use Sevaske\Payfort\Facades\Payfort;
use Sevaske\Payfort\Http\PayfortSignature;

class PayfortWebhookSignature
{
    protected array $payloadExcludedKeys = [
        'signature',
    ];

    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws PayfortSignatureException
     * @throws PayfortInvalidRequestException
     */
    public function handle(Request $request, Closure $next)
    {
        $merchantName = $request->route('merchant', 'default');
        $merchant = Payfort::merchant($merchantName);
        $payload = $this->getPayload($request);
        $status = $payload['status'] ?? null;

        // a request with the "Invalid Request" status does not include a signature
        if ($status === PaymentApiResponseStatus::InvalidRequest) {
            throw (new PayfortInvalidRequestException('Payfort webhook. Invalid request.'))
                ->withContext([
                    'uri' => $request->getUri(),
                    'merchant' => $merchant->getName(),
                    'payload' => $payload,
                ]);
        }

        // must have a signature
        if (! isset($payload['signature'])) {
            throw (new PayfortSignatureException('Signature is missing.'))
                ->withContext([
                    'uri' => $request->getUri(),
                    'merchant' => $merchantName,
                    'payload' => $payload,
                ]);
        }

        $calculatedSignature = (new PayfortSignature(
            $merchant->getCredentials()->getShaResponsePhrase(),
            $merchant->getCredentials()->getShaType()
        ))->calculateSignature(Arr::except($payload, $this->payloadExcludedKeys));

        if ($calculatedSignature !== $payload['signature']) {
            throw (new PayfortSignatureException('Invalid signature in a webhook request.'))
                ->withContext([
                    'uri' => $request->getUri(),
                    'merchant' => $merchant->getName(),
                    'calculated_signature' => $calculatedSignature,
                    'payload' => $payload,
                ]);
        }

        return $next($request);
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
