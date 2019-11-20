<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Forum;
use App\Domain\Repository\ForumRepositoryInterface;
use App\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method \App\Domain\Entity\Forum[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Forum[] save($entity)
 */
class ForumDoctrineRepository extends DoctrineRepository implements ForumRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    // public function nextIdentity(): ForumId
    // {
    //     return new ForumId(Uuid::random());
    // }
}
