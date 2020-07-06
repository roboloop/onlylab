<?php

namespace OnlyTracker\Infrastructure\Util\Parser\Title;

class GenreParser
{
    /**
     * @param string $content
     *
     * @return string[]
     */
    public function parse(string $content)
    {
        // If title is invalid
        if (mb_substr_count($content, '[') !== mb_substr_count($content, ']')) {
            preg_match('~[\[(]([^\[\]()]*)[\])]\s*$~i', $content, $matches);            
        } else {
            preg_match('~\[(?!.*\[)(.*)\]~i', $content, $matches);
        }
        if (!isset($matches[1])) {
            return [];
        }

        $splitted = preg_split('~\.|,~', $matches[1], null, PREG_SPLIT_NO_EMPTY);

        // Except:
        // 4k, 5k, UltraHD, two number together, months
        $except = implode('|', array_merge(
            $this->qualities(),
            $this->months(),
            $this->reservedWords(),
        ));
        $filtered = preg_grep("~$except~iu", $splitted, PREG_GREP_INVERT);

        // Check, if genres separated by space-symbol.
        if (count($filtered) === 1 && false === mb_strpos(reset($filtered), ',')) {
            $filtered = preg_split('~\s~', reset($filtered), null, PREG_SPLIT_NO_EMPTY);
        }

        return array_values(array_map('trim', $filtered));
    }

    private function qualities()
    {
        return [
            '\d{2}',
            '4k',
            '5k',
            '6k',
            '7k',
            '8k',
            'UltraHD',
        ];
    }

    private function months()
    {
        return [
           'january',
           'february',
           'march',
           'april',
           'may',
           'june',
           'july',
           'august',
           'september',
           'october',
           'november',
           'december'
        ];
    }

    private function reservedWords()
    {
        return [
            'SiteRip',
        ];
    }
}
