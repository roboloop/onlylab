<?php

namespace App\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class SearchQuery
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(?array $forumIds, ?array $genres, ?array $genreIds)
    {
        $qb = $this->entityManager->createQueryBuilder();

        if (null !== $forumIds) {
            $qb->andWhere('t.forum IN (:forumIds)');
            $qb->setParameter('forumIds', $forumIds);
        }

        if (null !== $genres) {
            $qb->andWhere('g.title LIKE :genre1');
            $qb->setParameter('genre1', $genreIds[0]);
        }

        if (null !== $genreIds) {
            $qb->andWhere('g.id IN :genreIds');
            $qb->setParameter('genreIds', $genreIds);
        }


    }
}
