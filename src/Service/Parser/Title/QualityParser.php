<?php

namespace App\Parser\Title;

use App\Contract\Parser\ParserInterface;

class QualityParser implements ParserInterface
{
    public function parse(string $string)
    {
        preg_match('~(\d{3,4}p)~i', $string, $result);

        return $result[1] ?? null;
    }
}
