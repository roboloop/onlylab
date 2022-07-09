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
        preg_match('#\b(\d{3,4}p)\b#i', $string, $pixel);

        if (isset($pixel[1])) {
            return $pixel[1];
        }

        preg_match('~(\d{3,4}p|4k|UltraHD)~i', $string, $result);

        if (isset($result[1]) && (0 === mb_strcasecmp($result[1], '4k') || 0 === mb_strcasecmp($result[1], 'UltraHD'))) {
            $result[1] = '2160p';
        }

        return $result[1] ?? null;
    }
}
