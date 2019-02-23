<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class YearParser implements ParserInterface
{
    public function parse(string $content)
    {
        // TODO:

        return $matches[1] ?? null;
    }
}
