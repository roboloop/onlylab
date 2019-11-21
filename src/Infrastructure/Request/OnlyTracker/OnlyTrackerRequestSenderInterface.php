<?php

namespace OnlyTracker\Infrastructure\Request\OnlyTracker;

use OnlyTracker\Infrastructure\Request\RequestInterface;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OnlyTrackerRequestSenderInterface implements RequestSenderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $client;

    public function __construct(HttpClientInterface $onlyTrackerClient)
    {
        $this->client = $onlyTrackerClient;
        $this->logger = new NullLogger;
    }

    public function send(RequestInterface $request)
    {
        try {
            $response   = $this->client->request($request->method(), $request->url(), $request->options());
            $content    = $response->getContent();
        } catch (HttpExceptionInterface $e) {
            $this->logger->warning($e->getTraceAsString());
        } catch (TransportExceptionInterface $e) {
            $this->logger->warning($e->getTraceAsString());
        } finally {
            $content = $content ?? null;
        }

        return $content;
    }
}
