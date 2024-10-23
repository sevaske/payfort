<?php

namespace Sevaske\Payfort\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Sevaske\Payfort\Exceptions\PayfortSignatureException;
use Sevaske\Payfort\Facades\Payfort;
use Sevaske\Payfort\Http\PayfortSignature;

class PayfortWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws PayfortSignatureException
     */
    public function handle(Request $request, Closure $next)
    {
        $merchantName = $request->route('merchant', 'default');

        // must have a signature
        if (! $request->has('signature')) {
            throw (new PayfortSignatureException('Signature is missing.'))
                ->withContext([
                    'uri' => $request->getUri(),
                    'merchant' => $merchantName,
                ]);
        }

        $requestSignature = $request->post('signature');
        $payload = $request->except('signature');
        $merchant = Payfort::merchant($merchantName);

        $calculatedSignature = (new PayfortSignature(
            $merchant->getCredentials()->getShaResponsePhrase(),
            $merchant->getCredentials()->getShaType()
        ))->calculateSignature($payload);

        if ($calculatedSignature !== $requestSignature) {
            throw (new PayfortSignatureException('Invalid signature in a webhook request.'))
                ->withContext([
                    'uri' => $request->getUri(),
                    'merchant' => $merchant->getName(),
                    'calculated_signature' => $calculatedSignature,
                    'request_signature' => $requestSignature,
                ]);
        }

        return $next($request);
    }
}
