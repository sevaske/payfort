<?php

namespace Sevaske\Payfort\Services\Http;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Sevaske\Payfort\Enums\PaymentApiResponseStatus;
use Sevaske\Payfort\Exceptions\PayfortRequestException;
use Sevaske\Payfort\Exceptions\PayfortResponseException;
use Sevaske\Payfort\Services\Merchant\PayfortCredentials;

class PayfortResponse
{
    private mixed $data = [];

    /**
     * @throws PayfortResponseException
     * @throws PayfortRequestException
     */
    public function __construct(private ResponseInterface $response, protected PayfortCredentials $credentials)
    {
        try {
            $this->data = (array) json_decode($this->response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            // invalid request
            if (($this->data['status'] ?? null) === PaymentApiResponseStatus::InvalidRequest->value) {
                throw new PayfortRequestException('Invalid request.');
            }

            // has a valid signature in response
            $this->validateSignature();
        } catch (JsonException $e) {
            throw new PayfortResponseException("Cannot encode response: {$e->getMessage()}", $e->getCode());
        }
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
     * @throws PayfortResponseException
     */
    protected function validateSignature(): void
    {
        $responseContent = $this->data;
        unset($responseContent['signature']);

        // no signature in response
        if (! ($this->data['signature'] ?? null)) {
            throw new PayfortResponseException('No signature in response.');
        }

        $service = new PayfortSignature($this->credentials->getShaResponsePhrase(), $this->credentials->getShaType());

        if ($service->calculateSignature($responseContent) !== $this->data['signature']) {
            throw new PayfortResponseException('Invalid signature in response.');
        }
    }
}
