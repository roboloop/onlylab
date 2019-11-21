<?php

namespace OnlyTracker\Infrastructure\Util\Parser\Title;

class YearParser
{
    /**
     * @param string $content
     *
     * @return string|null
     */
    public function parse(string $content)
    {
        preg_match('~\D+(20\d{2})\D+~', $content, $matches);

        return $matches[1] ?? null;
    }
}
