<?php

namespace OnlyTracker\Infrastructure\Util\Parser\Title;

class OriginalTitleParser
{
    /**
     * @param string $content
     *
     * @return null|string
     */
    public function parse(string $content)
    {
        preg_match('~\]\s*([^\[]+)\s*\[~', $content, $matches);

        if (!isset($matches[1])) {
            preg_match('~\]\s*(.*)\s*\(~', $content, $matches);
            if (isset($matches[1])) {
                return trim($matches[1]);
            }

            // if no studious
            preg_match('~^([^\[\]]+)~', $content, $matches);
            if (isset($matches[1])) {
                return trim($matches[1]);
            }

            return null;
        }

        return trim($matches[1]);
    }
}
