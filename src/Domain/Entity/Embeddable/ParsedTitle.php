<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\Embeddable;

class ParsedTitle
{
    private $rawTitle;
    private $title;
    private $year;
    private $quality;

    public function __construct(string $rawTitle, ?string $title, ?string $year, ?string $quality)
    {
        $this->rawTitle = $rawTitle;
        $this->title    = $title;
        $this->year     = $year;
        $this->quality  = $quality;
    }

    public function getRawTitle(): string
    {
        return $this->rawTitle;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function getQuality(): ?string
    {
        return $this->quality;
    }
}
