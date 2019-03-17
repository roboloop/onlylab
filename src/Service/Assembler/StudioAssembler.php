<?php

namespace App\Service\Assembler;

use App\Entity\Studio;

class StudioAssembler
{
    public function makeMany(array $urls)
    {
        return array_map(function ($url) {
            return $this->make($url);
        }, $urls);
    }

    public function make(string $url)
    {
        return (new Studio())->setUrl($url);
    }
}
