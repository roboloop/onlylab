<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Repository\ForumRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;

final class ForumDoctrineRepository extends DoctrineRepository implements ForumRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Forum::class);
    }

    /**
     * @return \OnlyTracker\Domain\Identity\ForumId
     * @deprecated
     */
    public function nextIdentity(): ForumId
    {
        return ForumId::random();
    }
}
