<?php

namespace Sevaske\Payfort\Http;

use Sevaske\Payfort\Config;
use Sevaske\Payfort\Contracts\CredentialsContract;
use Sevaske\Payfort\Credentials;
use Sevaske\Payfort\Exceptions\PayfortRequestException;
use Sevaske\Payfort\Exceptions\PayfortResponseException;

class PayfortRequest
{
    private string $method = 'POST';

    private string $uri = '/FortAPI/paymentApi/';

    protected ?Credentials $credentials;

    protected array $options = [];

    public function __construct(protected PayfortHttpClient $http)
    {
        $this->options = $this->getDefaultOptions();
    }

    /**
     * @throws PayfortRequestException
     * @throws PayfortResponseException
     */
    public function make(): PayfortResponse
    {
        $response = $this->http->request($this->method, $this->uri, $this->options);

        return new PayfortResponse($response, $this->credentials);
    }

    public function setCredentials(CredentialsContract $credentials): static
    {
        $this->credentials = $credentials;

        $this->setOptions([
            'json' => [
                'merchant_identifier' => $credentials->getMerchantIdentifier(),
                'access_code' => $credentials->getAccessCode(),
            ],
        ]);

        return $this;
    }

    public function setOptions(array $params): static
    {
        $this->options = array_replace_recursive($this->options, $params);

        return $this;
    }

    public function replaceOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setUri(string $uri): static
    {
        $this->uri = $uri;

        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @throws PayfortRequestException
     */
    public function calculateSignature(): static
    {
        if (! $this->credentials) {
            throw new PayfortRequestException('Cannot calculate signature. No credentials provided.');
        }

        $service = new PayfortSignature($this->credentials->getShaRequestPhrase(), $this->credentials->getShaType());
        $this->setSignature($service->calculateSignature($this->options['json']));

        return $this;
    }

    public function setSignature(string $signature): static
    {
        $this->setOptions([
            'json' => [
                'signature' => $signature,
            ],
        ]);

        return $this;
    }

    protected function getDefaultOptions(): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'language' => Config::getLanguage(),
            ],
        ];
    }
}
