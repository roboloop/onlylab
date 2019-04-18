<?php

namespace App\Service\Request;

use App\Client\TrackerClient;
use GuzzleHttp\Exception\GuzzleException;

class RequestSender
{
    private $client;

    public function __construct(TrackerClient $client)
    {
        $this->client = $client;
    }

    public function send(Request $request)
    {
        $uri        = $request->getUri();
        $method     = $request->getMethod();
        $options    = $request->getOptions();

        try {
            $response = $this->client->request($method, $uri, $options);
            $content = $response->getBody()->getContents();
        }
        catch (GuzzleException $exception) {
            return null;
        }

        return $content;
    }
}
