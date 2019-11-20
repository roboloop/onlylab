<?php

namespace App\Domain\Repository;

use App\Domain\Shared\PersistenceRepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;

/**
 * @method \App\Domain\Entity\Studio[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Studio[] findAll()
 * @method \App\Domain\Entity\Studio[] save($entity)
 */
interface StudioPersistenceRepositoryInterface extends PersistenceRepositoryInterface, IdGeneratorInterface
{

}
