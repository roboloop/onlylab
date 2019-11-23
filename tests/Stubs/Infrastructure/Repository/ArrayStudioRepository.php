<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Repository\StudioRepositoryInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Studio[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Studio[] findAll()
 * @method \OnlyTracker\Domain\Entity\Studio[] save($genre)
 */
class ArrayStudioRepository extends ArrayRepository implements StudioRepositoryInterface
{
}