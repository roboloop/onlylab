<?php

namespace App\Domain\Repository;

use App\Domain\Shared\RepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;

/**
 * @method \App\Domain\Entity\Genre[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Genre[] findAll()
 * @method \App\Domain\Entity\Genre[] save($entity)
 */
interface GenreRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{

}
