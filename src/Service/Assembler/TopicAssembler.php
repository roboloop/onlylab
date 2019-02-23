<?php

namespace App\Service\Assembler;

use App\Bag\Bag;
use App\Entity\Topic;
use App\Resolver\DateResolver;
use App\Resolver\SizeResolver;
use App\Service\TitleParserService;
use App\Validator\TopicBagValidator;

class TopicAssembler
{
    /** @var \App\Validator\TopicBagValidator */
    private $bagValidator;

    /** @var \App\Service\TitleParserService */
    private $titleParser;

    /** @var \App\Resolver\SizeResolver */
    private $sizeResolver;

    /** @var \App\Resolver\DateResolver */
    private $dateResolver;

    public function __construct(
        TopicBagValidator $bagValidator,
        TitleParserService $titleParser,
        SizeResolver $sizeResolver,
        DateResolver $dateResolver
    ) {
        $this->bagValidator = $bagValidator;
        $this->titleParser  = $titleParser;
        $this->sizeResolver = $sizeResolver;
        $this->dateResolver = $dateResolver;
    }

    /**
     * @param \App\Bag\Bag $bag
     *
     * @return \App\Entity\Topic
     */
    public function make(Bag $bag)
    {
        $this->bagValidator->validate($bag);
        $quality        = $this->titleParser->getQuality($bag['title']);
        $year           = $this->titleParser->getYear($bag['title']);
        $studio         = $this->titleParser->getStudio($bag['title']);
        $size           = $this->sizeResolver->resolve($bag['size']);
        $trackerCreatedAt   = $this->dateResolver->resolve($bag['trackerCreatedAt']);
        $releaseAt      = $this->dateResolver->resolve($bag['releaseAt']);

        $topic = new Topic();
        return $topic
            ->setTitle($bag['title'])
            ->setStudio($studio)
            ->setYear($year)
            ->setQuality($quality)
            ->setSize($size)
            ->setTrackerCreatedAt($trackerCreatedAt)
            ->setTrackerId($bag['trackerId'])
            ->setReleaseAt($releaseAt);
    }
}
