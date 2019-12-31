<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Identity\StudioId;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;

final class StudioDoctrineRepository extends DoctrineRepository implements StudioRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Studio::class);
    }

    public function nextIdentity(): StudioId
    {
        return new StudioId(Uuid::random());
    }
}
