<?php

namespace App\Domain\Repository;

use App\Domain\Shared\PersistenceRepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;

/**
 * @method \App\Domain\Entity\Image[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Image[] findAll()
 * @method \App\Domain\Entity\Image save($entity)
 */
interface ImagePersistenceRepositoryInterface extends PersistenceRepositoryInterface, IdGeneratorInterface
{

}
