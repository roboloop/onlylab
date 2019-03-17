<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class YearParser implements ParserInterface
{
    public function parse(string $content)
    {
        preg_match('~\D+(20\d{2})\D+~', $content, $matches);

        return $matches[1] ?? null;
    }
}
