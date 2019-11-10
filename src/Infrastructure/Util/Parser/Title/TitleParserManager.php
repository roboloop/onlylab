<?php

namespace App\Infrastructure\Util\Parser\Title;

class TitleParserManager
{
    private $genreParser;
    private $originalTitleParser;
    private $qualityParser;
    private $studioParser;
    private $yearParser;

    public function __construct(
        GenreParser $genreParser,
        OriginalTitleParser $originalTitleParser,
        QualityParser $qualityParser,
        StudioParser $studioParser,
        YearParser $yearParser
    ) {
        $this->genreParser          = $genreParser;
        $this->originalTitleParser  = $originalTitleParser;
        $this->qualityParser        = $qualityParser;
        $this->studioParser         = $studioParser;
        $this->yearParser           = $yearParser;
    }

    /**
     * @param string $title
     *
     * @return string[]
     */
    public function genres(string $title)
    {
        return $this->genreParser->parse($title);
    }

    /**
     * @param string $title
     *
     * @return null|string
     */
    public function originalTitle(string $title)
    {
        return $this->originalTitleParser->parse($title);
    }

    /**
     * @param string $title
     *
     * @return null|string
     */
    public function quality(string $title)
    {
        return $this->qualityParser->parse($title);
    }

    /**
     * @param string $title
     *
     * @return string[]
     */
    public function studios(string $title)
    {
        return $this->studioParser->parse($title);
    }

    /**
     * @param string $title
     *
     * @return null|string
     */
    public function year(string $title)
    {
        return $this->yearParser->parse($title);
    }
}
