<?php

namespace App\QueryBuilder;

use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;

class SearchQueryBuilder
{
    /** @var \Doctrine\ORM\QueryBuilder */
    private $qb;
    private $parameters;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->init($entityManager);
    }

    private function init(EntityManagerInterface $entityManager)
    {
        $this->qb = $entityManager
            ->createQueryBuilder()
            ->select('t')
            ->from(Topic::class, 't');

        $this->parameters = new ArrayCollection();
    }

    public function addForumIds(?array $forumIds)
    {
        if (null !== $forumIds) {
            $this->qb->andWhere('t.forum IN (:forumIds)');
            $this->parameters[] = new Parameter('forumIds', $forumIds, Connection::PARAM_INT_ARRAY);
            // $this->qb->setParameter('forumIds', $forumIds);
        }
    }

    public function addGenres(?array $genres)
    {
        if (null !== $genres) {
            $this->qb->andWhere('g.title LIKE :genre1');
            $this->qb->setParameter('genre1', $genreIds[0]);
        }
    }

    public function addGenreIds(?array $genreIds)
    {
        if (null !== $genreIds and count($genreIds)) {
            $this->qb->andWhere('g.title IN (:genres)');
            $this->parameters[] = new Parameter('genres', $genreIds, Connection::PARAM_INT_ARRAY);
        }
    }

    public function getQuery()
    {
        $this->qb->setParameters($this->parameters);

        return $this->qb->getQuery();
    }
}
