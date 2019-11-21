<?php

namespace OnlyTracker\Infrastructure\Request;

interface RequestInterface
{
    public function url(): string;

    public function method(): string;

    public function options(): array;
}
