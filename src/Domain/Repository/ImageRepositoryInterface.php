<?php

namespace OnlyTracker\Domain\Repository;

use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Image[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Image save($entity)
 * @method \OnlyTracker\Domain\Entity\Image saveMultiple(array $entities)
 */
interface ImageRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{

}
