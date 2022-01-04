<?php

namespace EasyBroker;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class EasyBroker
{
    public const RESULT_NOT_FOUND = 'result_not_found';

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $base_url = 'https://api.stagingeb.com/v1';

    /** @var string */
    protected $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    private function getHeaders(): array
    {
        return [
            'headers' => [
                'accept' => 'application/json',
                "X-Authorization" => $this->apiKey
            ]
        ];
    }

    public function getProperty($id): array
    {
        try {
            $endpoint = $this->base_url . '/properties/' . $id;
            $response = $this->client->request('GET', $endpoint, $this->getHeaders());

            $apiResponse = json_decode($response->getBody());

            return $this->formattedProperty($apiResponse);
        } catch (ClientException $exception) {
            if ($exception->getCode() == 404) {
                return $this->emptyResponse();
            }
        }
    }
    

    public function getProperties(array $parameters = []): array
    {
        $payload = $this->getRequestPayload($parameters);
        $endpoint = $this->base_url . '/properties';

        $response = $this->client->request('GET', $endpoint, array_merge($this->getHeaders(), $payload));       

        $apiResponse = json_decode($response->getBody());

        if (!count($apiResponse->content)) {
            return $this->emptyResponse();
        }

        return $this->formatResponse($apiResponse);
    }

    public function printProperties()
    {
        $properties = $this->getProperties();

        foreach ($properties as $property) {
            echo $property['title'];
        }
    }


    protected function formatResponse($response): array
    {
        $properties = array_map(function ($result) {
            return $this->formattedProperty($result);
        }, $response->content);

        return $properties;
    }

    protected function getRequestPayload(array $parameters = []): array
    {
        $parameters = array_merge([
            'limit' => 20
        ], $parameters);

        return ['query' => $parameters];
    }

    protected function emptyResponse(): array
    {
        return [
            'title' => static::RESULT_NOT_FOUND,
        ];
    }

    protected function formattedProperty($property): array
    {
        return [
            'id' => $property->public_id,
            'title' => $property->title,
        ];
    }
}
