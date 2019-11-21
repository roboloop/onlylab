<?php

namespace OnlyTracker\Infrastructure\Util\Parser\Title;

class QualityParser
{
    /**
     * @param string $string
     *
     * @return string|null
     */
    public function parse(string $string)
    {
        preg_match('~(\d{3,4}p|4k|UltraHD)~i', $string, $result);

        return $result[1] ?? null;
    }
}
