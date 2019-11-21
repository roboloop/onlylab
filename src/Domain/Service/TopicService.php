<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Factory\TopicFactory;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use DateTimeImmutable;

class TopicService
{
    private $topicFactory;
    private $parsedTitleService;
    private $topicRepository;

    public function __construct(TopicFactory $topicFactory, ParsedTitleService $parsedTitleService, TopicRepositoryInterface $topicRepository)
    {
        $this->topicFactory         = $topicFactory;
        $this->parsedTitleService   = $parsedTitleService;
        $this->topicRepository      = $topicRepository;
    }

    public function makeNotLoaded(int $exId, string $rawTitle, Forum $forum, ?int $size, ?DateTimeImmutable $exCreatedAt)
    {
        $parsedTitle = $this->parsedTitleService->make($rawTitle);

        $topic = $this->topicFactory->make($exId, $parsedTitle, $forum, $size, $exCreatedAt, false);

        $this->topicRepository->save($topic);

        return $topic;
    }
}
