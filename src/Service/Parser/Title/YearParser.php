<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class YearParser implements ParserInterface
{
    public function parse(string $content)
    {
        preg_match('~(20\d{2})~', $content, $matches);

        return $matches[1] ?? null;
    }
}
