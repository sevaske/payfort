<?php

namespace Sevaske\Payfort\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Sevaske\Payfort\Config;
use Sevaske\Payfort\Exceptions\PayfortRequestException;

class PayfortHttpClient
{
    private Client $client;

    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @throws PayfortRequestException
     */
    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        try {
            $response = $this->client->request($method, $uri, $options);

            if (Config::isDebugMode()) {
                Log::channel(Config::getLogChannel())->info('Payfort Request and Response', [
                    'request' => [
                        'method' => $method,
                        'uri' => $uri,
                        'options' => $options,
                    ],
                    'response' => [
                        'status' => $response->getStatusCode(),
                        'body' => $response->getBody()->getContents(),
                    ],
                ]);
            }

            return $response;
        } catch (GuzzleException $e) {
            throw new PayfortRequestException($e->getMessage(), $e->getCode());
        }
    }
}
