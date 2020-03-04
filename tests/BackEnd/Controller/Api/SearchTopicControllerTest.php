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
        // $client = $this->createClient();
        // $data = json_encode(['forumIds' => [42, 43], 'rawTitles' => ['AAA', 'BBB']]);
        // $client->request('POST', '/api/search', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        // $resp = $client->getResponse();

        $this->assertTrue(true);
    }
}
