<?php

namespace App\Service\Sanitizer;

use App\Contract\Service\Sanitizer\SanitizerInterface;

class CachedSanitizer implements SanitizerInterface
{
    private $sanitizer;
    private $cached = [];

    public function __construct(SanitizerInterface $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function sanitize(string $content)
    {
        $hash = md5($content);

        if (!isset($this->cached[$hash])) {
            $this->cached[$hash] = $this->sanitizer->sanitize($content);
        }

        return $this->cached[$hash];
    }
}
