<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Identity\GenreId;
use OnlyTracker\Domain\Repository\GenreRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenreDoctrineRepository extends DoctrineRepository implements GenreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function nextIdentity(): GenreId
    {
        return new GenreId(Uuid::random());
    }
}
