<?php

namespace OnlyTracker\Domain\Repository;

use OnlyTracker\Domain\Identity\GenreId;
use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Genre   find($id)
 * @method \OnlyTracker\Domain\Entity\Genre[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Genre[] save($entity)
 * @method \OnlyTracker\Domain\Entity\Genre[] saveMultiple(array $entities)
 */
interface GenreRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{
    public function nextIdentity(): GenreId;
}
