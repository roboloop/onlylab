<?php

namespace App\Parser\Title;

use App\Contract\Parser\ParserInterface;

class StudioParser implements ParserInterface
{
    public function parse(string $content)
    {
        preg_match('~^\[([a-zA-Z0-9\/\\\.\s_-]+)\]~', $content, $matches);

        return $matches[1] ??  null;
    }
}
