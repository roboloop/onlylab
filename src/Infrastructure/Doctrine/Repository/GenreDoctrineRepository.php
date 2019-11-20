<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepositoryInterface;
use App\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method \App\Domain\Entity\Genre[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Genre[] save($entity)
 */
class GenreDoctrineRepository extends DoctrineRepository implements GenreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }
}
