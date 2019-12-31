<?php

namespace OnlyTracker\Tests\BackEnd\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchTopicControllerTest extends WebTestCase
{
    protected function setUp(): void
    {

    }

    public function testRequest()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/search');
        $resp = $client->getResponse();
    }
}
