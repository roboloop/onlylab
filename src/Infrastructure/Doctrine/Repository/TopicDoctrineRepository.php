<?php

declare (strict_types = 1);

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Search\TopicSearchCriteria;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;
use OnlyTracker\Shared\Infrastructure\DoctrineRepository;

final class TopicDoctrineRepository extends DoctrineRepository implements TopicRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Topic::class);
    }

    public function nextIdentity(): string
    {
        return Uuid::random();
    }

    public function totalTopics(Criteria $criteria): int
    {
        return (int) $this->entityManager->createQueryBuilder()
            ->select('COUNT(t.id)')
            ->from($this->entityClass, 't')
            ->addCriteria($criteria)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function search(TopicSearchCriteria $criteria)
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb
            // ->select('t', 'f', 's', 'g', 'i')
            ->select('t.id')
            ->distinct()
            ->from($this->entityClass, 't')
            ->innerJoin('t.forum', 'f')
            ->innerJoin('t.studios', 's')
            ->innerJoin('t.genres', 'g')
        ;

        // Identifiers
        if (null !== $criteria->getTopicsIds()) {
            $qb->andWhere('t.id IN (:topicIds)');
            $qb->setParameter('topicIds', $criteria->getTopicsIds());
        }

        if (null !== $criteria->getForumIds()) {
            $qb->andWhere('t.forum IN (:forumIds)');
            $qb->setParameter('forumIds', $criteria->getForumIds());
        }

        if (null !== $criteria->getStudioIds()) {
            $qb->andWhere('t.studios IN (:studioIds)');
            $qb->setParameter('studioIds', $criteria->getStudioIds());
        }
        // End of Identifiers

        // Parsed title
        if (null !== $criteria->getTitles()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getTitles(), 't.parsedTitle.title');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getRawTitles()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getRawTitles(), 't.parsedTitle.rawTitle');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getYears()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getYears(), 't.parsedTitle.year');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getQualities()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getQualities(), 't.parsedTitle.quality');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }
        // End of Parsed title

        // Studios
        if (null !== $criteria->getStudioUrls()) {
            // $this->andStudioUrlsLikeExpr($qb, $criteria->getStudioUrls());
            $this->andStudioLikeExpr($qb, $criteria->getStudioUrls());
        }

        if (null !== $criteria->getStudioStatuses()) {
            $values = array_diff(
                StudioStatus::all(),
                array_map(fn (StudioStatus $status) => (string) $status, $criteria->getStudioStatuses())
            );

            if (count($values)) {
                $this->andStudioStatusesLikeExpr($qb, $values);
            }
        }
        // End of studios

        // Genres
        if (null !== $criteria->getGenreIds()) {
            $qb->andWhere('t.genres IN (:genreIds)');
            $qb->setParameter('genreIds', $criteria->getGenreIds());
        }

        if (null !== $criteria->getGenreTitles()) {
            // $this->andGenreTitleLikeExpr($qb, $criteria->getGenreTitles());
            $this->andGenreLikeExpr($qb, $criteria->getGenreTitles());
        }

        // if (null !== $criteria->getIsApproved()) {
        //     $qb->andWhere('g.isApproved = :isApproved');
        //     $qb->setParameter('isApproved', $criteria->getIsApproved());
        // }
        // End of Genres

        $mainQb = $this->entityManager->createQueryBuilder();

        $mainQb
            ->select('m_t', 'm_f', 'm_s', 'm_g', 'm_i')
            ->from($this->entityClass, 'm_t')
            ->leftJoin('m_t.forum', 'm_f')
            ->leftJoin('m_t.studios', 'm_s')
            ->leftJoin('m_t.genres', 'm_g')
            ->leftJoin('m_t.images', 'm_i')
            ->andWhere('m_t.id IN (' . $qb->getDQL() . ')')
            ->addOrderBy('m_t.createdAt', 'DESC')
            ->addOrderBy('m_g.title', 'ASC')
        ;

        $qbParameters = $qb->getParameters();
        $mainQbParameters = $mainQb->getParameters();

        $mainQb->setParameters(
            new ArrayCollection([...$qbParameters, ...$mainQbParameters])
        );

        return $mainQb->getQuery()->getResult();
    }

    // private function andStudioUrlsLikeExpr(QueryBuilder $qb, array $values)
    // {
    //     $subQb = $this->entityManager
    //         ->createQueryBuilder()
    //         ->select('s_t')
    //         ->from(Topic::class, 's_t')
    //         ->innerJoin('s_t.studios', 's_s')
    //     ;
    //
    //     list($andNotLike, $params, $args) = $this->util->andNotLikeExpr($values, 's_s.url');
    //     $subQb->andWhere($andNotLike);
    //
    //     for ($i = 0; $i < count($params); $i++) {
    //         $qb->setParameter($params[$i], $args[$i]->getValue());
    //     }
    //
    //     $qb->andWhere(sprintf('t.id NOT IN (%s)', $subQb->getDQL()));
    // }

    // private function andStudioStatusesLikeExpr(QueryBuilder $qb, array $values)
    // {
    //     $subQb = $this->entityManager
    //         ->createQueryBuilder()
    //         ->select('ss_t')
    //         ->from(Topic::class, 'ss_t')
    //         ->innerJoin('ss_t.studios', 'ss_s')
    //         ->andWhere('ss_s.status.value NOT IN (:ss_s_values)')
    //     ;
    //
    //     $qb->setParameter('ss_s_values', $values);
    //     $qb->andWhere(sprintf('t.id NOT IN (%s)', $subQb->getDQL()));
    // }
    //
    // private function andGenreTitleLikeExpr(QueryBuilder $qb, array $values)
    // {
    //     $subQb = $this->entityManager
    //         ->createQueryBuilder()
    //         ->select('s_t')
    //         ->from(Topic::class, 'g_t')
    //         ->innerJoin('g_t.genres', 'g_g')
    //     ;
    //
    //     list($andNotLike, $params, $args) = $this->util->andNotLikeExpr($values, 'g_g.title');
    //     $subQb->andWhere($andNotLike);
    //
    //     for ($i = 0; $i < count($params); $i++) {
    //         $qb->setParameter($params[$i], $args[$i]->getValue());
    //     }
    //
    //     $qb->andWhere(sprintf('t.id NOT IN (%s)', $subQb->getDQL()));
    // }

    private function andGenreLikeExpr(QueryBuilder $qb, array $values)
    {
        $i = 0;
        $dql = $params = [];
        foreach ($values as $value) {
            $params[] = $param = "sub_g$i.title";
            $dql[]  = "0 != (SELECT COUNT(sub_gt$i) FROM " . Topic::class . " sub_gt$i INNER JOIN sub_gt$i.genres sub_g$i WHERE sub_gt$i = t AND $param LIKE :g_value$i)";
            $qb->setParameter("g_value$i", '%' . $value . '%');
            $i++;
        }

        $qb->andWhere(implode(' AND ', $dql));
    }

    private function andStudioStatusesLikeExpr(QueryBuilder $qb, array $values)
    {
        $i = 0;
        $dql = $params = [];

        foreach ($values as $value) {
            $params[] = $param = "sub_ss$i.status.value";
            $dql[]  = "0 = (SELECT COUNT(DISTINCT(sub_sst$i)) FROM " . Topic::class . " sub_sst$i INNER JOIN sub_sst$i.studios sub_ss$i WHERE sub_sst$i = t AND $param = :ss_value$i)";
            $qb->setParameter("ss_value$i", (string) $value);
            $i++;
        }

        $qb->andWhere(implode(' AND ', $dql));
    }

    private function andStudioLikeExpr(QueryBuilder $qb, array $values)
    {
        $i = 0;
        $dql = $params = [];
        foreach ($values as $value) {
            $params[] = $param = "sub_s$i.url";
            $dql[]  = "0 != (SELECT COUNT(sub_st$i) FROM " . Topic::class . " sub_st$i INNER JOIN sub_st$i.studios sub_s$i WHERE sub_st$i = t AND $param LIKE :s_value$i)";
            $qb->setParameter("s_value$i", '%' . $value . '%');
            $i++;
        }

        $qb->andWhere(implode(' AND ', $dql));
    }
}
