<?php

namespace App\Assembler;

use App\Entity\Studio;

class StudioAssembler
{
    public function make(array $urls)
    {
        return array_map(function ($url) {
            return (new Studio())->setUrl($url);
        }, $urls);
    }
}
