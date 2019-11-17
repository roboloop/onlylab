<?php

namespace App\Infrastructure\Request;

interface RequestSenderInterface
{
    public function send(RequestInterface $request);
}
