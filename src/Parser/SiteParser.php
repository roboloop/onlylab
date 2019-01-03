<?php

namespace App\Parser;

use App\Contract\Parser;

class SiteParser implements Parser
{
    public function parse(string $string): string
    {
        preg_match('~\[(.+)\]~iU', $string, $result);

        return $result[1] ?? '';
    }
}
