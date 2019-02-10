<?php

namespace App\Parser\Title;

use App\Contract\Parser\ParserInterface;

class GenreParser implements ParserInterface
{
    public function parse(string $string): string
    {
        preg_match('~\[(?!.*\[)(.*)\]~i', $string, $result);

        return $result[1] ?? '';
    }
}
