<?php

namespace OnlyTracker\Domain\Repository;

use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Forum[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Forum[] save($entity)
 * @method \OnlyTracker\Domain\Entity\Forum[] saveMultiple(array $entities)
 */
interface ForumRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{
    // public function nextIdentity(): ForumId;
}
