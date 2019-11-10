<?php

namespace App\Tests\Stubs\Infrastructure\Repository;

use App\Domain\Repository\StudioRepositoryInterface;

/**
 * @method \App\Domain\Entity\Studio[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Studio[] findAll()
 * @method \App\Domain\Entity\Studio[] save($genre)
 */
class ArrayStudioRepository extends ArrayRepository implements StudioRepositoryInterface
{
}