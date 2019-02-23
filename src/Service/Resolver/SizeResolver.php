<?php

namespace App\Service\Resolver;

class SizeResolver
{
    public function resolve(string $size)
    {
        // TODO: if there's a postfix, then it's one thing; if not, then it's bytes (good).
        return 1;
    }
}
