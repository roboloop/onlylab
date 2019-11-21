<?php

namespace OnlyTracker\Infrastructure\Request;

interface RequestSenderInterface
{
    public function send(RequestInterface $request);
}
