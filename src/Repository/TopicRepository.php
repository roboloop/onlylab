<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    public function findExistsTrackerIds(array $ids)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('t.trackerId AS id')
            ->from($this->_entityName, 't')
            ->where('t.trackerId IN (:ids)')
            ->setParameter('ids', $ids);

        $query = $qb->getQuery();

        return $query->getScalarResult();
    }

    public function removeCompletelyQuery(array $ids)
    {
        $qb = $this->_em->createQueryBuilder()
            ->delete($this->_entityName, 't')
            ->where('t.id IN (:ids)')
            ->innerJoin('t.genres', 'g')
            ->setParameter('ids', $ids);

        return $qb->getQuery();
    }

    public function removeCompletely(array $ids)
    {
        $query = $this->removeCompletelyQuery($ids);

        return $query->execute();
    }
}
