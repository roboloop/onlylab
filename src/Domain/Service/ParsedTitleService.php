<?php

namespace App\Domain\Service;

use App\Domain\Factory\ParsedTitleFactory;
use App\Infrastructure\Util\Parser\Title\TitleParserManager;

class ParsedTitleService
{
    private $parsedTitleFactory;
    private $parserManager;

    public function __construct(ParsedTitleFactory $parsedTitleFactory, TitleParserManager $parserManager)
    {
        $this->parsedTitleFactory   = $parsedTitleFactory;
        $this->parserManager        = $parserManager;
    }

    public function make(string $rawTitle)
    {
        $title      = $this->parserManager->originalTitle($rawTitle);
        $year       = $this->parserManager->year($rawTitle);
        $quality    = $this->parserManager->quality($rawTitle);

        return $this->parsedTitleFactory->make($rawTitle, $title, $year, $quality);
    }
}
