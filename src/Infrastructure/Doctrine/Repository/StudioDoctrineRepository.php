<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class StudioDoctrineRepository extends DoctrineRepository implements StudioRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Studio::class);
    }
}
