<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Factory\TopicFactory;
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

    public function makeNotLoaded(int $exId, string $rawTitle, Forum $forum, ?int $size, ?DateTimeImmutable $exCreatedAt)
    {
        $parsedTitle = $this->parsedTitleService->make($rawTitle);

        $topic = $this->topicFactory->make($exId, $parsedTitle, $forum, $size, $exCreatedAt, false);

        $this->topicRepository->save($topic);

        return $topic;
    }
}
