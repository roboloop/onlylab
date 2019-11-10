<?php

namespace App\Domain\Repository;

use App\Domain\Shared\RepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;

/**
 * @method \App\Domain\Entity\Forum[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Forum[] findAll()
 * @method \App\Domain\Entity\Forum[] save($entity)
 */
interface ForumRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{

}
