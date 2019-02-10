<?php

namespace App\Parser\Title;

use App\Contract\Parser\Parser;

class GenreParser implements Parser
{
    public function parse(string $string): string
    {
        preg_match('~\[(?!.*\[)(.*)\]~i', $string, $result);

        return $result[1] ?? '';
    }
}
