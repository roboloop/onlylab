<?php

namespace App\Service\UrlConverter\Image;

use App\Contract\Service\UrlConverter\UrlConverterInterface;

class Fastpic implements UrlConverterInterface
{
    public function convert($url)
    {
        $url = preg_replace('~thumb~', 'big', $url);
        $url = preg_replace('~jpeg$~', 'jpg', $url);

        return $url . '?noht=1';
    }

    public function support($url)
    {
        return preg_match('~fastpic\.ru~', $url);
    }
}
