<?php

namespace App\Service\Assembler;

use App\Dto\ForumLineDto;
use App\Entity\Topic;
use App\Service\Parser\Title\SizeParser;
use App\Service\TitleParserService;

class TopicAssembler
{
    private $titleParser;
    private $sizeParser;

    public function __construct(
        TitleParserService $titleParser,
        SizeParser $sizeParser
    ) {
        $this->titleParser  = $titleParser;
        $this->sizeParser = $sizeParser;
    }

    /**
     * @param \App\Dto\ForumLineDto $dto
     *
     * @return \App\Entity\Topic
     */
    public function make(ForumLineDto $dto)
    {
        $trackerId      = $dto->getTrackerId();
        $quality    = $this->titleParser->getQuality($dto->getTitle());
        $year       = $this->titleParser->getYear($dto->getTitle());
        $size       = $this->sizeParser->parse($dto->getSize());

        return ($topic = new Topic())
            ->setTitle($dto->getTitle())
            ->setYear($year)
            ->setQuality($quality)
            ->setSize($size)
            ->setTrackerId($trackerId);
    }
}
