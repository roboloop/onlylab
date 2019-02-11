<?php

namespace App\Repository;

use App\Entity\Studio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Studio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studio[]    findAll()
 * @method Studio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Studio::class);
    }

    public function existsByUrl(array $urls)
    {
        $qb = $this->createQueryBuilder('s');
        $this->addWhereConditions($qb, $urls, 's', 'url', true);

        return $qb
            ->getQuery()
            ->getResult();
    }

    private function addWhereConditions(QueryBuilder $qb, array $clauses, string $alias, string $column, bool $strict)
    {
        foreach ($clauses as $index => $clause) {
            $conditions[] = "$alias.$column LIKE :string$index";
            $qb->setParameter("string$index", $strict ? $clause : "%$clause%");
        }

        $qb->andWhere($qb->expr()->orX(...$conditions ?? []));
    }
}
