<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Repository\ForumRepositoryInterface;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ForumDoctrineRepository extends DoctrineRepository implements ForumRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    // public function nextIdentity(): ForumId
    // {
    //     return new ForumId(Uuid::random());
    // }
}
