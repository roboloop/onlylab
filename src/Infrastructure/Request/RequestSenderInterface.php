<?php

namespace OnlyTracker\Infrastructure\Request;

interface RequestSenderInterface
{
    /**
     * @param \OnlyTracker\Infrastructure\Request\RequestInterface $request
     *
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ExceptionInterface
     */
    public function send(RequestInterface $request): string;

    /**
     * @param string $url
     *
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ExceptionInterface
     */
    public function sendRaw(string $url): string;
}
