<?php

namespace Sevaske\Payfort\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Sevaske\Payfort\Config;
use Sevaske\Payfort\Contracts\ServiceRequestContract;
use Sevaske\Payfort\Credentials;
use Sevaske\Payfort\Exceptions\PayfortRequestException;
use Sevaske\Payfort\Exceptions\PayfortResponseException;
use Sevaske\Payfort\Http\PayfortRequest;
use Sevaske\Payfort\Http\PayfortResponse;
use Sevaske\Payfort\Http\Requests\CaptureServiceRequest;
use Sevaske\Payfort\Http\Requests\CheckStatusServiceRequest;
use Sevaske\Payfort\Http\Requests\CreateTokenServiceRequest;
use Sevaske\Payfort\Http\Requests\RecurringServiceRequest;
use Sevaske\Payfort\Http\Requests\RefundServiceRequest;
use Sevaske\Payfort\Http\Requests\UpdateTokenServiceRequest;
use Sevaske\Payfort\Http\Requests\VoidAuthorizationServiceRequest;

class ApiServices
{
    public function __construct(protected ?Credentials $credentials = null) {}

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function request(string $method = 'POST', string $uri = '/FortAPI/paymentApi', array $options = []): PayfortResponse
    {
        return $this->buildRequest($method, $uri, $options)->make();
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function capture(int $amount, string $currency, array $extra = []): PayfortResponse
    {
        $this->makeServiceRequest(new CaptureServiceRequest($this->credentials), [
            'amount' => $amount,
            'currency' => $currency,
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function checkStatus(?string $merchantReference = null, ?int $fortId = null, array $extra = []): PayfortResponse
    {
        if (empty($merchantReference) && $fortId === null) {
            throw new PayfortRequestException('Either merchant reference or fortId is required.');
        }

        return $this->makeServiceRequest(new CheckStatusServiceRequest($this->credentials), [
            ...array_filter([
                'merchant_reference' => $merchantReference,
                'fort_id' => $fortId,
            ]),
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function createToken(
        string $merchantReference,
        int $cardNumber,
        int $expiryDate,
        string $returnUrl,
        array $extra = [],
    ): PayfortResponse {
        return $this->makeServiceRequest(new CreateTokenServiceRequest($this->credentials), [
            'merchant_reference' => $merchantReference,
            'card_number' => $cardNumber,
            'expiry_date' => $expiryDate,
            'return_url' => $returnUrl,
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function recurring(
        string $merchantReference,
        int $amount,
        string $currency,
        string $customerEmail,
        string $tokenName,
        array $extra = [],
    ): PayfortResponse {
        return $this->makeServiceRequest(new RecurringServiceRequest($this->credentials), [
            'merchant_reference' => $merchantReference,
            'amount' => $amount,
            'currency' => $currency,
            'customer_email' => $customerEmail,
            'token_name' => $tokenName,
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function refund(
        int $amount,
        string $currency,
        ?string $merchantReference = null,
        ?int $fortId = null,
        array $extra = [],
    ): PayfortResponse {
        if (empty($merchantReference) && $fortId === null) {
            throw new PayfortRequestException('Either merchant reference or fortId is required.');
        }

        return $this->makeServiceRequest(new RefundServiceRequest($this->credentials), [
            'amount' => $amount,
            'currency' => $currency,
            ...array_filter([
                'merchant_reference' => $merchantReference,
                'fort_id' => $fortId,
            ]),
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function updateToken(string $merchantReference, string $tokenName, array $extra = []): PayfortResponse
    {
        return $this->makeServiceRequest(new UpdateTokenServiceRequest($this->credentials), [
            'merchant_reference' => $merchantReference,
            'token_name' => $tokenName,
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function voidAuthorization(
        ?string $merchantReference = null,
        ?int $fortId = null,
        array $extra = [],
    ): PayfortResponse {
        return $this->makeServiceRequest(new VoidAuthorizationServiceRequest($this->credentials), [
            ...array_filter([
                'merchant_reference' => $merchantReference,
                'fort_id' => $fortId,
            ]),
            ...$extra,
        ]);
    }

    /**
     * @throws PayfortRequestException
     */
    public function buildRequest(string $method, string $uri, array $options): PayfortRequest
    {
        $request = app(PayfortRequest::class)
            ->setUri($uri)
            ->setMethod($method)
            ->setOptions($options);

        // credentials and signature
        if ($this->credentials) {
            $request
                ->setCredentials($this->credentials)
                ->calculateSignature();
        }

        return $request;
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function makeServiceRequest(ServiceRequestContract $service, array $data): PayfortResponse
    {
        $request = $this->buildRequest($service->getMethod(), $service->getUri(), ['json' => [
            ...$service->getPreparedRequestData(),
            ...$data,
        ]]);

        // validate prepared request
        if (Config::isValidationRequestsEnabled() && $rules = $service->rules()) {
            try {
                Validator::validate($request->getOptions()['json'], $rules);
            } catch (ValidationException $e) {
                throw (new PayfortRequestException('Validation failed: '.$e->getMessage(), $e->getCode(), $e))
                    ->withContext([
                        'service' => $service::class,
                        'data' => $data,
                        'rules' => $rules,
                        'errors' => $e->errors(),
                    ]);
            }
        }

        return $request->make();
    }
}
