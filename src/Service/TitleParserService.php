<?php

namespace App\Service;

use App\Loader\ParsersLoader;
use App\Service\Parser\Title\GenreParser;
use App\Service\Parser\Title\QualityParser;
use App\Service\Parser\Title\StudioParser;
use App\Service\Parser\Title\YearParser;

class TitleParserService
{
    /** @var \App\Loader\ParsersLoader */
    private $loader;

    public function __construct(ParsersLoader $loader)
    {
        $this->loader = $loader;
    }

    public function getQuality(string $title)
    {
        return $this->loader->getParser(QualityParser::class)->parse($title);
    }

    public function getYear(string $title)
    {
        return $this->loader->getParser(YearParser::class)->parse($title);
    }

    public function getStudios(string $title)
    {
        return $this->loader->getParser(StudioParser::class)->parse($title);
    }

    public function getGenres(string $title)
    {
        return $this->loader->getParser(GenreParser::class)->parse($title);
    }
}
