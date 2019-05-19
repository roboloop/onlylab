<?php

namespace App\Service\Parser\Title;

use App\Contract\Parser\ParserInterface;

class GenreParser implements ParserInterface
{
    public function parse(string $content)
    {
        preg_match('~\[(?!.*\[)(.*)\]~i', $content, $matches);

        if (!isset($matches[1])) {
            return [];
        }

        $splitted = preg_split('~\.|,~', $matches[1], null, PREG_SPLIT_NO_EMPTY);

        // Except:
        // 4k, 5k, UltraHD, two number together, months
        $except = implode('|', array_merge($this->qualities(), $this->months()));
        $filtered = preg_grep("~$except~iu", $splitted, PREG_GREP_INVERT);

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
}
