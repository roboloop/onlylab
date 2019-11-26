<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Identity\StudioId;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class StudioDoctrineRepository extends DoctrineRepository implements StudioRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Studio::class);
    }

    public function nextIdentity(): StudioId
    {
        return new StudioId(Uuid::random());
    }
}
