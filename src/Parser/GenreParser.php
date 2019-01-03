<?php

namespace App\Parser;

use App\Contract\Parser;

class GenreParser implements Parser
{
    public function parse(string $string): string
    {
        preg_match('~\[(?!.*\[)(.*)\]~i', $string, $result);

        return $result[1] ?? '';
    }
}
