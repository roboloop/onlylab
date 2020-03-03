<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use OnlyTracker\Domain\Entity\Image;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;

final class ImageDoctrineRepository extends DoctrineRepository implements ImageRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Image::class);
    }

    public function nextIdentity(): string
    {
        return Uuid::random();
    }
}
