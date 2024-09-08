<?php

namespace Sevaske\Payfort\Http;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Sevaske\Payfort\Credentials;
use Sevaske\Payfort\Enums\PaymentApiResponseStatus;
use Sevaske\Payfort\Exceptions\PayfortRequestException;
use Sevaske\Payfort\Exceptions\PayfortResponseException;

class PayfortResponse
{
    private mixed $data = [];

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function __construct(private readonly ResponseInterface $response, protected ?Credentials $credentials)
    {
        try {
            $this->parseResponse();
            $this->validateResponse();
        } catch (JsonException $e) {
            throw new PayfortResponseException("Cannot encode response: {$e->getMessage()}", $e->getCode());
        }
    }

    /**
     * @throws JsonException
     */
    protected function parseResponse(): void
    {
        $this->data = (array) json_decode($this->response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    protected function validateResponse(): void
    {
        $this->checkStatus();
        $this->checkSignature();
    }

    /**
     * @throws PayfortRequestException
     */
    protected function checkStatus(): void
    {
        $status = $this->data['status'] ?? null;

        if (! $status) {
            return;
        }

        if ($status === PaymentApiResponseStatus::InvalidRequest->value) {
            throw new PayfortRequestException('Invalid request.');
        }
    }

    /**
     * @throws PayfortResponseException
     */
    protected function checkSignature(): void
    {
        if (! $this->credentials) {
            return;
        }

        // no signature in response
        if (! ($this->data['signature'] ?? null)) {
            throw new PayfortResponseException('No signature in response.');
        }

        $responseContent = $this->data;
        unset($responseContent['signature']);

        $service = new PayfortSignature($this->credentials->getShaResponsePhrase(), $this->credentials->getShaType());

        if ($service->calculateSignature($responseContent) !== $this->data['signature']) {
            throw new PayfortResponseException('Invalid signature in response.');
        }
    }
}
