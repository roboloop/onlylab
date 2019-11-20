<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Topic;
use App\Domain\Repository\TopicRepositoryInterface;
use App\Shared\Infrastructure\DoctrineRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method \App\Domain\Entity\Topic[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \App\Domain\Entity\Topic[] save($entity)
 */
class TopicDoctrineRepository extends DoctrineRepository implements TopicRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }
}
