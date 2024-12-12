<?php

namespace Sevaske\Payfort\Http;

use Psr\Http\Message\ResponseInterface;
use Sevaske\Payfort\Contracts\ResponseContract;
use Sevaske\Payfort\Credentials;
use Sevaske\Payfort\Exceptions\PayfortResponseException;

class PayfortResponse implements ResponseContract
{
    private mixed $data = [];

    /**
     * @throws PayfortResponseException
     */
    public function __construct(private readonly ResponseInterface $response, protected ?Credentials $credentials)
    {
        $this->parseResponse();
        $this->checkSignature();
    }

    protected function parseResponse(): void
    {
        $this->data = (array) json_decode($this->response->getBody(), true);
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
