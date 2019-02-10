<?php

namespace App\Parser\Title;

use App\Contract\Parser\Parser;

class QualityParser implements Parser
{
    public function parse(string $string): string
    {
        preg_match('~(\d{3,4}p)~i', $string, $result);

        return $result[1] ?? '';
    }
}
