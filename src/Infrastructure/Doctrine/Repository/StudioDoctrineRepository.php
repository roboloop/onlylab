<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Studio;
use App\Domain\Repository\StudioRepositoryInterface;
use App\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method \App\Domain\Entity\Studio[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Studio[] save($entity)
 */
class StudioDoctrineRepository extends DoctrineRepository implements StudioRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Studio::class);
    }
}
