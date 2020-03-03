<?php

namespace OnlyTracker\Infrastructure\Request\OnlyTracker;

use OnlyTracker\Infrastructure\Request\RequestInterface;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OnlyTrackerRequestSender implements RequestSenderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $client;

    public function __construct(HttpClientInterface $onlyTrackerClient)
    {
        $this->client = $onlyTrackerClient;
        $this->logger = new NullLogger;
    }

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request): string
    {
        $response   = $this->client->request($request->method(), $request->url(), $request->options());
        $content    = $response->getContent();

        return $content;
    }
}
