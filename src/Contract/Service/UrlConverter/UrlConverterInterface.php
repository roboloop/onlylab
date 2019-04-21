<?php

namespace App\Contract\Service\UrlConverter;

interface UrlConverterInterface
{
    public function convert($data);

    public function support($data);
}
