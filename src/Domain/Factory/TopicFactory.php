<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Embeddable\ParsedTitle;
use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;
use DateTimeImmutable;

class TopicFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(TopicRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(int $exId, ParsedTitle $parsedTitle, Forum $forum, ?int $size, ?DateTimeImmutable $exCreatedAt, bool $isLoaded)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Topic($id, $exId, $parsedTitle, $forum, $size, $exCreatedAt, $createdAt, $isLoaded);
    }
}
