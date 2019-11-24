<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use OnlyTracker\Domain\Entity\Image;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ImageDoctrineRepository extends DoctrineRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }
}
