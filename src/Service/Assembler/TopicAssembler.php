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
        $quality    = $this->titleParser->getQuality($dto->getRawTitle());
        $year       = $this->titleParser->getYear($dto->getRawTitle());
        $size       = $this->sizeParser->parse($dto->getSize());
        $title      = $this->titleParser->getOriginalTitle($dto->getRawTitle());

        return ($topic = new Topic())
            ->setRawTitle($dto->getRawTitle())
            ->setTitle($title)
            ->setYear($year)
            ->setQuality($quality)
            ->setSize($size)
            ->setTrackerId($trackerId);
    }

    public function makeMany(array $dtos)
    {
        return array_map(function ($dto) {
            return $this->make($dto);
        }, $dtos);
    }
}
