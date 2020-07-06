<?php

namespace OnlyTracker\Domain\Repository;

use Doctrine\Common\Collections\Criteria;
use OnlyTracker\Domain\Search\TopicSearchCriteria;
use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

/**
 * @method \OnlyTracker\Domain\Entity\Topic   find($id)
 * @method \OnlyTracker\Domain\Entity\Topic[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method \OnlyTracker\Domain\Entity\Topic[] save($entity)
 * @method \OnlyTracker\Domain\Entity\Topic[] saveMultiple(array $entities)
 */
interface TopicRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{
    /**
     * @param \OnlyTracker\Domain\Search\TopicSearchCriteria $criteria
     *
     * @return \OnlyTracker\Domain\Entity\Topic[]
     */
    public function search(TopicSearchCriteria $criteria);

    public function totalTopics(Criteria $criteria): int;
}
