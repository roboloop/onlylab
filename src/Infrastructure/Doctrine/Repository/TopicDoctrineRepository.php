<?php

declare (strict_types = 1);

namespace OnlyTracker\Infrastructure\Doctrine\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Query\QueryBuilder as NativeQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use OnlyTracker\Domain\Entity\Enum\GenreStatus;
use OnlyTracker\Domain\Entity\Enum\StudioStatus;
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
        return (string) Uuid::random();
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

    public function searchTotal(TopicSearchCriteria $criteria): int
    {
         return (int) $this->searchQueryBuilder($criteria)
             ->getQuery()
             ->setFirstResult(null)
             ->setMaxResults(null)
             ->setHint(Query::HINT_CUSTOM_TREE_WALKERS, [CountWalker::class])
             ->getSingleScalarResult()
            ;
    }

    public function search(TopicSearchCriteria $criteria): array
    {
        return $this->searchQueryBuilder($criteria)
            ->getQuery()
            ->getResult();
    }

    public function searchQueryBuilder(TopicSearchCriteria $criteria): QueryBuilder
    {
        $offset = ($criteria->getPage() - 1) * $criteria->getPerPage();
        $limit = $criteria->getPerPage();
        
        $qb = $this->createNativeQueryBuilder();
        
        $qb
            // ->select('t.*', 'f.*', 'st.*', 's.*', 'gt.*', 'g.*', 'i.*')
            ->distinct()
            ->select('t.id')
            ->from('topics', 't')
            ->leftJoin('t', 'forums', 'f', 'f.id = t.forum_id')
            ->leftJoin('t', 'studio_topic', 'st', 't.id = st.topic_id')
            ->leftJoin('st', 'studios', 's', 's.id = st.studio_id')
            ->leftJoin('t', 'genre_topic', 'gt', 't.id = gt.topic_id')
            ->leftJoin('gt', 'genres', 'g', 'g.id = gt.genre_id')
            ->leftJoin('t', 'images', 'i', 't.id = i.topic_id')
            ->addOrderBy('t.created_at', 'DESC')
            ->addOrderBy('g.title', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        // Identifiers
        if (null !== $criteria->getTopicsIds()) {
            $qb->andWhere('t.id IN (:topicIds)');
            $qb->setParameter(':topicIds', $criteria->getTopicsIds(), Connection::PARAM_INT_ARRAY);
        }
        
        if (null !== $criteria->getForumIds()) {
            $qb->andWhere('t.forum_id IN (:forumIds)');
            $qb->setParameter(':forumIds', $criteria->getForumIds(), Connection::PARAM_INT_ARRAY);
        }
        // End of Identifiers
        
        // Parsed title
        if (null !== $criteria->getTitles()) {
            [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($criteria->getTitles(), 't.title');
            $this->dbalUtil->andWhere($qb, $sqlPart, $args);
        }
        
        if (null !== $criteria->getRawTitles()) {
            [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($criteria->getRawTitles(), 't.raw_title');
            $this->dbalUtil->andWhere($qb, $sqlPart, $args);
        }
        
        if (null !== $criteria->getYears()) {
            [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($criteria->getYears(), 't.year');
            $this->dbalUtil->andWhere($qb, $sqlPart, $args);
        }
        
        if (null !== $criteria->getQualities()) {
            [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($criteria->getQualities(), 't.quality');
            $this->dbalUtil->andWhere($qb, $sqlPart, $args);
        }
        // End of Parsed title
        
        // Genres
        if (null !== $criteria->getGenreTitles()) {
            $this->addRawGenreLikeExpr($qb, $criteria->getGenreTitles());
        }

        $this->addRawGenreUnbannedExpr($qb);

        // Studios
        if (null !== $criteria->getStudioUrls()) {
            $this->addRawStudioLikeExpr($qb, $criteria->getStudioUrls());
        }
        
        if (null !== ($values = $criteria->getStudioStatuses()) && count($values)) {
            $this->addRawStudioStatusesLikeExpr($qb, $values);
        }

        // $rsm = new ResultSetMappingBuilder($this->entityManager, ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT);
        // $rsm->addRootEntityFromClassMetadata(Topic::class, 't');
        // $rsm->addJoinedEntityFromClassMetadata(Image::class, 'i', 't', 'images');
        // $rsm->addJoinedEntityFromClassMetadata(Forum::class, 'f', 't', 'forum');
        // $rsm->addJoinedEntityFromClassMetadata(Studio::class, 's', 't', 'studios');
        // $rsm->addJoinedEntityFromClassMetadata(Genre::class, 'g', 't', 'genres');
        //
        // $subSql = $qb->getSQL();
        // $sql = $this
        //     ->createNativeQueryBuilder()
        //     ->select((string) $rsm)
        //     ->from('topics', 't')
        //     ->leftJoin('t', 'forums', 'f', 'f.id = t.forum_id')
        //     ->leftJoin('t', 'studio_topic', 'st', 't.id = st.topic_id')
        //     ->leftJoin('st', 'studios', 's', 's.id = st.studio_id')
        //     ->leftJoin('t', 'genre_topic', 'gt', 't.id = gt.topic_id')
        //     ->leftJoin('gt', 'genres', 'g', 'g.id = gt.genre_id')
        //     ->leftJoin('t', 'images', 'i', 't.id = i.topic_id')
        //     ->where("t.id IN ($subSql)")
        //     ->setParameters($qb->getParameters(), $qb->getParameterTypes())
        //     ->setFirstResult($offset)
        //     ->setMaxResults($limit)
        //     ->getSQL();
        //
        // return $this->entityManager->createNativeQuery($sql, $rsm)
        //     ->setParameters($qb->getParameters())
        //     ->getResult();

        $ids = array_column($qb->execute()->fetchAll(), 'id');
        
        return dd($this->entityManager
            ->createQueryBuilder()
            ->select('t', 'f', 's', 'g', 'i')
            ->from($this->entityClass, 't')
            ->leftJoin('t.forum', 'f')
            ->leftJoin('t.studios', 's')
            ->leftJoin('t.genres', 'g')
            ->leftJoin('t.images', 'i')
            ->andWhere('t.id IN (:ids)')
            ->addOrderBy('t.createdAt', 'DESC')
            ->addOrderBy('g.title', 'ASC')
            ->setParameter('ids', $ids)
            ->getQuery()->getSQL()
        )
            ;
    }

    /**
     * @param \OnlyTracker\Domain\Search\TopicSearchCriteria $criteria
     *
     * @return \OnlyTracker\Domain\Entity\Topic[]|mixed
     * @deprecated 
     */
    public function searchDeprecated(TopicSearchCriteria $criteria)
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
                StudioStatus::ALL_STATUSES,
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
            ->setFirstResult(($criteria->getPage() - 1) * $criteria->getPerPage())
            ->setMaxResults($criteria->getPerPage())
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

    private function addRawGenreLikeExpr(NativeQueryBuilder $qb, array $values)
    {
        $subQb = $this->createNativeQueryBuilder();
        $subQb
            ->select('gt.topic_id')
            ->from('genres', 'g')
            ->innerJoin('g', 'genre_topic', 'gt', 'g.id = gt.genre_id')
            ;

        [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($values, 'g.title');
        $this->dbalUtil->andWhere($subQb, $sqlPart, $args);

        $sql = $subQb->getSQL();
        $qb->andWhere("t.id IN ($sql)");
        $this->dbalUtil->mergeParameters($qb, $subQb);
    }

    private function addRawGenreUnbannedExpr(NativeQueryBuilder $qb)
    {
        // Only checked
        $markedQb = $this->createNativeQueryBuilder();
        $markedQb
            ->select('gt.topic_id')
            ->from('genres', 'g')
            ->innerJoin('g', 'genre_topic', 'gt', 'g.id = gt.genre_id')
        ;

        $values = [GenreStatus::UNBANNED];
        [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($values, 'g.status', 'mark');
        $this->dbalUtil->andWhere($markedQb, $sqlPart, $args);
        $markedSql = $markedQb->getSQL();

        $qb->andWhere("t.id IN ($markedSql)");
        $this->dbalUtil->mergeParameters($qb, $markedQb);

        // Only non checked
        $noMarkedQb = $this->createNativeQueryBuilder();
        $noMarkedQb
            ->select('gt.topic_id')
            ->from('genres', 'g')
            ->innerJoin('g', 'genre_topic', 'gt', 'g.id = gt.genre_id')
        ;

        $invert = array_values(array_diff(
            GenreStatus::ALL_STATUSES,
            array_map(fn (GenreStatus $status) => (string) $status, $values)
        ));

        if (empty($invert)) {
            return;
        }

        [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($invert, 'g.status', 'nomark');
        $this->dbalUtil->andWhere($noMarkedQb, $sqlPart, $args);
        $noMarkedSql = $noMarkedQb->getSQL();

        $qb->andWhere("t.id NOT IN ($noMarkedSql)");
        $this->dbalUtil->mergeParameters($qb, $noMarkedQb);
    }

    private function addRawStudioLikeExpr(NativeQueryBuilder $qb, array $values)
    {
        $subQb = $this->createNativeQueryBuilder();
        $subQb
            ->select('st.topic_id')
            ->from('studios', 's')
            ->innerJoin('s', 'studio_topic', 'st', 's.id = st.studio_id')
        ;

        [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($values, 's.url');
        $this->dbalUtil->andWhere($subQb, $sqlPart, $args);

        $sql = $subQb->getSQL();
        $qb->andWhere("t.id IN ($sql)");
        $this->dbalUtil->mergeParameters($qb, $subQb);
    }

    private function addRawStudioStatusesLikeExpr(NativeQueryBuilder $qb, array $values)
    {
        // Only checked
        $markedQb = $this->createNativeQueryBuilder();
        $markedQb
            ->select('st.topic_id')
            ->from('studios', 's')
            ->innerJoin('s', 'studio_topic', 'st', 's.id = st.studio_id')
        ;

        [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($values, 's.status', 'mark');
        $this->dbalUtil->andWhere($markedQb, $sqlPart, $args);
        $markedSql = $markedQb->getSQL();

        $qb->andWhere("t.id IN ($markedSql)");
        $this->dbalUtil->mergeParameters($qb, $markedQb);
        
        // Only non checked
        $noMarkedQb = $this->createNativeQueryBuilder();
        $noMarkedQb
            ->select('st.topic_id')
            ->from('studios', 's')
            ->innerJoin('s', 'studio_topic', 'st', 's.id = st.studio_id')
        ;
        
        $invert = array_values(array_diff(
            StudioStatus::ALL_STATUSES,
            array_map(fn (StudioStatus $status) => (string) $status, $values)
        ));
        
        if (empty($invert)) {
            return;
        }
        
        [$sqlPart, $args] = $this->dbalUtil->orLikeExpr($invert, 's.status', 'nomark');
        $this->dbalUtil->andWhere($noMarkedQb, $sqlPart, $args);
        $noMarkedSql = $noMarkedQb->getSQL();
        
        $qb->andWhere("t.id NOT IN ($noMarkedSql)");
        $this->dbalUtil->mergeParameters($qb, $noMarkedQb);
    }
}
