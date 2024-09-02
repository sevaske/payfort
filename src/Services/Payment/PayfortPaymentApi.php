<?php

namespace Sevaske\Payfort\Services\Payment;

use Sevaske\Payfort\Config;
use Sevaske\Payfort\Enums\PaymentCommand;
use Sevaske\Payfort\Exceptions\PayfortRequestException;
use Sevaske\Payfort\Exceptions\PayfortResponseException;
use Sevaske\Payfort\Services\Http\PayfortHttpClient;
use Sevaske\Payfort\Services\Http\PayfortResponse;
use Sevaske\Payfort\Services\Http\PayfortSignature;
use Sevaske\Payfort\Services\Merchant\PayfortCredentials;

class PayfortPaymentApi
{
    protected array $preparedRequestData = [];

    public function __construct(
        protected PayfortHttpClient $http,
        protected PayfortCredentials $credentials,
    ) {
        $this->fillPreparedRequestData();
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function authorization(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::Authorization->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function purchase(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::Purchase->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function tokenization(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::Tokenization->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function capture(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::Capture->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function voidAuthorization(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::VoidAuthorization->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function refund(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::Refund->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function checkStatus(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::CheckStatus->value, $params);
    }

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function recurring(array $params): PayfortResponse
    {
        return $this->command(PaymentCommand::Recurring->value, $params);
    }

    public function http(): PayfortHttpClient
    {
        return $this->http;
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function command(string $command, array $params): PayfortResponse
    {
        $options = [
            'headers' => $this->prepareHeader(),
            'json' => [
                'query_command' => $command,
                ...$this->preparedRequestData,
                ...$params,
            ],
        ];

        // signature
        $options['json']['signature'] = (new PayfortSignature(
            $this->credentials->getShaRequestPhrase(),
            $this->credentials->getShaType())
        )->calculateSignature($options['json']);

        // Psr\Http\Message\ResponseInterface
        $response = $this->http()->request('POST', '/FortAPI/paymentApi', $options);

        return new PayfortResponse($response, $this->credentials);
    }

    protected function prepareHeader(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    protected function fillPreparedRequestData(): void
    {
        $this->preparedRequestData = [
            'merchant_identifier' => $this->credentials->getMerchantIdentifier(),
            'access_code' => $this->credentials->getAccessCode(),
            'language' => Config::getLanguage(),
        ];
    }
}
