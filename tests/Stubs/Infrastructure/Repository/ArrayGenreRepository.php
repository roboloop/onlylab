<?php

namespace App\Tests\Stubs\Infrastructure\Repository;

use App\Domain\Repository\GenreRepositoryInterface;

/**
 * @method \App\Domain\Entity\Genre[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Genre[] findAll()
 * @method \App\Domain\Entity\Genre[] save($genre)
 */
class ArrayGenreRepository extends ArrayRepository implements GenreRepositoryInterface
{
}
