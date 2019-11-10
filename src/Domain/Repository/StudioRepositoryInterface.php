<?php

namespace App\Domain\Repository;

use App\Domain\Shared\RepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;

/**
 * @method \App\Domain\Entity\Studio[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Studio[] findAll()
 * @method \App\Domain\Entity\Studio[] save($entity)
 */
interface StudioRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{

}
