<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TopicDoctrineRepository extends DoctrineRepository implements TopicRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }
}
