<?php

namespace App\Service\Util;

use Symfony\Component\HttpFoundation\RequestStack;

class UrlResolver
{
    public function url(string $string)
    {
        $string = preg_replace(['~^./~', '~^/~'], '', $string);

        return 'http://tracker.net/forum/' . $string;
    }
}