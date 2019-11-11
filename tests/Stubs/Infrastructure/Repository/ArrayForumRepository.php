<?php

namespace App\Tests\Stubs\Infrastructure\Repository;

use App\Domain\Repository\ForumRepositoryInterface;

/**
 * @method \App\Domain\Entity\Forum[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Forum[] findAll()
 * @method \App\Domain\Entity\Forum[] save($entity)
 */
class ArrayForumRepository extends ArrayRepository implements ForumRepositoryInterface
{
}
