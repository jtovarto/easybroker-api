<?php

namespace EasyBroker\Test;

use GuzzleHttp\Client;
use EasyBroker\EasyBroker;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\GuzzleException;

class EasyBrokerTest extends TestCase
{
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
    public function it_can_get_properties()
    {
        $results = $this->easyBroker->getProperties();

        $this->assertArrayHasKey('id', $results[0]);
        $this->assertArrayHasKey('title', $results[0]);
    }

    /** @test */
    public function it_can_get_a_property_by_id()
    {
        $result = $this->easyBroker->getProperty('EB-B5403');

        $this->assertEquals($result['title'], 'Casa en Renta en Privanzas San Pedro Garza Garcia');
    }

    /** @test */
    public function it_should_return_an_empty_response_when_using_a_non_existing_property_id()
    {
        $result = $this->easyBroker->getProperty('WrongId');

        $this->assertEquals($result['title'], EasyBroker::RESULT_NOT_FOUND);
    }

    /** @test */
    public function it_can_throw_an_exception_wwhen_using_an_invalid_apikey()
    {
        $this->expectException(GuzzleException::class);

        $this->easyBroker->setApiKey('wrongApiKey');

        $results = $this->easyBroker->getProperties();
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
