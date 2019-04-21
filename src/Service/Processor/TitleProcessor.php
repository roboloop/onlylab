<?php

namespace App\Service\Processor;

use App\Service\TitleParserService;

class TitleProcessor
{
    private $titleParser;

    public function __construct(TitleParserService $titleParser)
    {
        $this->titleParser = $titleParser;
    }

    public function rawGenresFromTitle(string $title)
    {
        return $this->titleParser->getGenres($title);
    }

    public function rawStudiosFromTitle(string $title)
    {
        return $this->titleParser->getStudios($title);
    }
}
