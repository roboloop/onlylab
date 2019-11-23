<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Repository\GenreRepositoryInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Genre[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Genre[] findAll()
 * @method \OnlyTracker\Domain\Entity\Genre[] save($genre)
 */
class ArrayGenreRepository extends ArrayRepository implements GenreRepositoryInterface
{
}
