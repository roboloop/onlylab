<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class OriginalTitleParser implements ParserInterface
{
    public function parse(string $content)
    {
        preg_match('~\]\s*(.*)\s*\[~', $content, $matches);

        if (!isset($matches[1])) {
            return null;
        }

        return trim($matches[1]);
    }
}
