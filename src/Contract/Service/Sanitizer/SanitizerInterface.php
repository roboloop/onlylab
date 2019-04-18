<?php

namespace App\Contract\Service\Sanitizer;

interface SanitizerInterface
{
    public function sanitize(string $content);
}
