<?php

namespace OnlyTracker\Domain\Repository;

use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Topic[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Topic[] save($entity)
 * @method \OnlyTracker\Domain\Entity\Topic[] saveMultiple(array $entities)
 */
interface TopicRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{

}
