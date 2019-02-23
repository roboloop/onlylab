<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class SiteParser implements ParserInterface
{
    public function parse(string $string): string
    {
        preg_match('~\[(.+)\]~iU', $string, $result);

        return $result[1] ?? '';
    }
}
