<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class GenreParser implements ParserInterface
{
    public function parse(string $content)
    {
        preg_match('~\[(?!.*\[)(.*)\]~i', $content, $matches);

        if (!isset($matches[1]))
            return [];

        $filtered = preg_grep('~\d{2}~u', $matches[1], PREG_GREP_INVERT);

        return array_values(array_map(function ($value) {
            return trim($value);
        }, $filtered));
    }
}
