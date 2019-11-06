<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Embeddable\ParsedTitle;
use App\Domain\Entity\Forum;
use App\Domain\Entity\Topic;
use App\Domain\Repository\TopicRepositoryInterface;
use App\Domain\Shared\DateTimeUtilInterface;
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
