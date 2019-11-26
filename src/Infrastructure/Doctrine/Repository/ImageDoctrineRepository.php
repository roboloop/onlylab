<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Image;
use OnlyTracker\Domain\Identity\ImageId;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ImageDoctrineRepository extends DoctrineRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function nextIdentity(): ImageId
    {
        return new ImageId(Uuid::random());
    }
}
