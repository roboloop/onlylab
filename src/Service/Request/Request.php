<?php

namespace App\Service\Request;

abstract class Request
{
    abstract public function getUri();

    abstract public function getMethod();

    abstract public function getOptions();
}
