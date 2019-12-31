<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Identity\GenreId;
use OnlyTracker\Domain\Repository\GenreRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;

final class GenreDoctrineRepository extends DoctrineRepository implements GenreRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Genre::class);
    }

    public function nextIdentity(): GenreId
    {
        return new GenreId(Uuid::random());
    }
}
