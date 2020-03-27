<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Factory\TopicFactory;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use DateTimeImmutable;
use OnlyTracker\Domain\Search\TopicSearchCriteria;

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

    public function makeNotLoaded(string $id, string $rawTitle, Forum $forum, ?Size $size, ?DateTimeImmutable $exCreatedAt)
    {
        $parsedTitle = $this->parsedTitleService->make($rawTitle);

        $topic = $this->topicFactory->make($id, $parsedTitle, $forum, $size, $exCreatedAt, false);

        return $topic;
    }

    public function related(Topic $topic)
    {
        $parsedTitle = $topic->getParsedTitle();
        $title = $parsedTitle->getTitle();

        if (null === $title) {
            return [];
        }

        $criteria = (new TopicSearchCriteria())
            ->setTitles([
                $title,
            ]);

        $found = $this->topicRepository->search($criteria);

        return array_filter($found, fn(Topic $related) => $related->getId() !== $topic->getId());
    }

    public function getFullTopicById($topicId): Topic
    {
        $criteria = (new TopicSearchCriteria)
            ->setTopicIds([
                $topicId
            ]);

        $found = $this->topicRepository->search($criteria);

        return array_shift($found);
    }

    public function search(?string $title, ?string $year, ?string $quality)
    {
        // TODO:
        return $this->topicRepository->search(new TopicSearchCriteria);
    }

    public function searchByCriteria(TopicSearchCriteria $criteria)
    {
        return $this->topicRepository->search($criteria);
    }
}
