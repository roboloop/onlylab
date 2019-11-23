<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Repository\ImageRepositoryInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Image[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Image[] findAll()
 * @method \OnlyTracker\Domain\Entity\Image[] save($genre)
 */
class ArrayImageRepository extends ArrayRepository implements ImageRepositoryInterface
{
}