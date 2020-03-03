<?php

namespace OnlyTracker\Domain\Repository;

use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Studio   find($id)
 * @method \OnlyTracker\Domain\Entity\Studio[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Studio[] save($entity)
 * @method \OnlyTracker\Domain\Entity\Studio[] saveMultiple(array $entities)
 */
interface StudioRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{
    public function nextIdentity(): string;
}
