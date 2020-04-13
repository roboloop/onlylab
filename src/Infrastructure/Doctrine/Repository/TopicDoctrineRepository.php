<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
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

    public function search(TopicSearchCriteria $criteria)
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb
            // ->select('t', 'f', 's', 'g', 'i')
            ->select('t.id')
            ->distinct()
            ->from($this->entityClass, 't')
            ->leftJoin('t.forum', 'f')
            ->leftJoin('t.studios', 's')
            ->leftJoin('t.genres', 'g')
            ->andWhere(
                '0 = (SELECT COUNT(sub_t) FROM '. Topic::class . ' sub_t INNER JOIN sub_t.studios sub_s WHERE sub_t = t AND sub_s.status.value = :s_status)'
            )
            ->setParameter('s_status', StudioStatus::BANNED)
        ;

        if (null !== $criteria->getTopicsIds()) {
            $qb->andWhere('t.id IN (:topicIds)');
            $qb->setParameter('topicIds', $criteria->getTopicsIds());
        }

        if (null !== $criteria->getForumIds()) {
            $qb->andWhere('t.forum IN (:forumIds)');
            $qb->setParameter('forumIds', $criteria->getForumIds());
        }

        if (null !== $criteria->getRawTitles()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getRawTitles(), 't.rawTitle');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getStudioIds()) {
            $qb->andWhere('t.studios IN (:studioIds)');
            $qb->setParameter('studioIds', $criteria->getStudioIds());
        }

        if (null !== $criteria->getStudioUrls()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getStudioUrls(), 's.url');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getStudioStatuses()) {
            $qb->andWhere('s.status IN (:studioStatuses)');
            $qb->setParameter('studioStatuses', $criteria->getStudioStatuses());
        }

        if (null !== $criteria->getGenreIds()) {
            $qb->andWhere('t.genres IN (:genreIds)');
            $qb->setParameter('genreIds', $criteria->getGenreIds());
        }

        if (null !== $criteria->getGenreTitles()) {
            // list($orLike, $params, $args) = $this->util->andGenreLikeExpr($criteria->getGenreTitles(), 'g.title');
            $this->andGenreLikeExpr($qb, $criteria->getGenreTitles());
            // $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getIsApproved()) {
            $qb->andWhere('g.isApproved = :isApproved');
            $qb->setParameter('isApproved', $criteria->getIsApproved());
        }

        if (null !== $criteria->getTitles()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getTitles(), 't.parsedTitle.title');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getQualities()) {
            list($orLike, $params, $args) = $this->util->orLikeExpr($criteria->getQualities(), 't.parsedTitle.quality');
            $this->util->andWhere($qb, $orLike, $params, $args);
        }

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

    private function andGenreLikeExpr(QueryBuilder $qb, array $values)
    {
        $i = 0;
        $dql = $params = [];
        foreach ($values as $value) {
            $params[] = $param = "sub_g$i.title";
            $dql[]  = "0 != (SELECT COUNT(sub_t$i) FROM " . Topic::class . " sub_t$i INNER JOIN sub_t$i.genres sub_g$i WHERE sub_t$i = t AND $param LIKE :g_value$i)";
            $qb->setParameter("g_value$i", '%' . $value . '%');
            $i++;
        }

        $qb->andWhere(implode(' AND ', $dql));
    }
}
