<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Repository\ForumRepositoryInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Forum[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Forum[] findAll()
 * @method \OnlyTracker\Domain\Entity\Forum[] save($entity)
 */
class ArrayForumRepository extends ArrayRepository implements ForumRepositoryInterface
{
}
