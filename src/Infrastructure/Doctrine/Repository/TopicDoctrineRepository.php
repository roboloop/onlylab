<?php

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
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
            ->select('t', 'f', 's', 'g', 'i')
            ->from($this->entityClass, 't')
            ->innerJoin('t.forum', 'f')
            ->leftJoin('t.studios', 's')
            ->leftJoin('t.genres', 'g')
            ->leftJoin('t.images', 'i')
            ->orderBy('t.parsedTitle.title', 'ASC');

        if (null !== $criteria->getForumIds()) {
            $qb->andWhere('t.forum IN (:forumIds)');
            $qb->setParameter('forumIds', $criteria->getForumIds());
        }

        if (null !== $criteria->getRawTitles()) {
            list($orLike, $params, $args) = $this->orLikeExpr($criteria->getRawTitles(), 't.rawTitle');
            $this->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getStudioIds()) {
            $qb->andWhere('t.studios IN (:studioIds)');
            $qb->setParameter('studioIds', $criteria->getStudioIds());
        }

        if (null !== $criteria->getStudioUrls()) {
            list($orLike, $params, $args) = $this->orLikeExpr($criteria->getStudioUrls(), 's.url');
            $this->andWhere($qb, $orLike, $params, $args);
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
            list($orLike, $params, $args) = $this->orLikeExpr($criteria->getGenreTitles(), 'g.title');
            $this->andWhere($qb, $orLike, $params, $args);
        }

        if (null !== $criteria->getIsApproved()) {
            $qb->andWhere('g.isApproved = :isApproved');
            $qb->setParameter('isApproved', $criteria->getIsApproved());
        }

        if (null !== $criteria->getTitles()) {
            list($orLike, $params, $args) = $this->orLikeExpr($criteria->getTitles(), 't.title');
            $this->andWhere($qb, $orLike, $params, $args);
        }

        return $qb->getQuery()->getResult();
    }
}
