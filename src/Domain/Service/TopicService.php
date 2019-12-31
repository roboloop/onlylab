<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use OnlyTracker\Domain\Factory\TopicFactory;
use OnlyTracker\Domain\Identity\TopicId;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use DateTimeImmutable;

class TopicService
{
    private $topicRepository;
    private $topicFactory;
    private $parsedTitleService;

    public function __construct(TopicRepositoryInterface $topicRepository, TopicFactory $topicFactory, ParsedTitleService $parsedTitleService)
    {
        $this->topicRepository      = $topicRepository;
        $this->topicFactory         = $topicFactory;
        $this->parsedTitleService   = $parsedTitleService;
    }

    public function makeNotLoaded(TopicId $id, string $rawTitle, Forum $forum, ?Size $size, ?DateTimeImmutable $exCreatedAt)
    {
        $parsedTitle = $this->parsedTitleService->make($rawTitle);

        $topic = $this->topicFactory->make($id, $parsedTitle, $forum, $size, $exCreatedAt, false);

        return $topic;
    }

    public function search(?string $title, ?string $year, ?string $quality)
    {
        // TODO:
    }
}
