<?php

namespace EasyBroker\Test;

use PHPUnit\Framework\TestCase;
use EasyBroker\EasyBrokerProperties;

class EasyBrokerPropertiesTest extends TestCase
{
    /** @test */
    public function it_can_get_a_pro()
    {
        $limit = 5;

        $apiProperties = new EasyBrokerProperties;
        $apiProperties->getProperties(['limit' => $limit]);

        $this->assertIsArray($apiProperties->properties);
        $this->assertCount($limit, $apiProperties->properties);
    }
}
