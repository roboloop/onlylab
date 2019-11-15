<?php

namespace App\Tests\Stubs\Infrastructure\Repository;

use App\Domain\Repository\ImageRepositoryInterface;

/**
 * @method \App\Domain\Entity\Image[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Image[] findAll()
 * @method \App\Domain\Entity\Image[] save($genre)
 */
class ArrayImageRepository extends ArrayRepository implements ImageRepositoryInterface
{
}