<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Embeddable\ParsedTitle;

class ParsedTitleFactory
{
    public function make(string $rawTitle, ?string $title, ?string $year, ?string $quality)
    {
        return new ParsedTitle($rawTitle, $title, $year, $quality);
    }
}
