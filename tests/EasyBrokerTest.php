<?php

namespace EasyBroker\Test;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use EasyBroker\EasyBroker;

class EasyBrokerTest extends TestCase
{
    /** @var \Spatie\Geocoder\Geocoder */
    protected $easyBroker;

    public function setUp(): void
    {
        parent::setUp();

        $client = new Client();

        $this->easyBroker = new EasyBroker($client);
        

        if (!$apiKey = $this->getApiKey()) {
            $this->markTestSkipped('No API key was provided.');

            return;
        }

        $this->easyBroker->setApiKey($apiKey);
    }

    /** @test */
    public function it_can_get_a_city()
    {
        $result = $this->easybroker->getCoordinatesForAddress('Antwerp');

        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertArrayHasKey('accuracy', $result);
        $this->assertArrayHasKey('formatted_address', $result);
        $this->assertArrayHasKey('viewport', $result);
    }

    protected function getApiKey()
    {  
        $apiKeyPath = __DIR__ . '/../.apiKey';

        if (!file_exists($apiKeyPath)) {
            return;
        }

        return file_get_contents($apiKeyPath);

        
    }

}
