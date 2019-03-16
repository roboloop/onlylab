<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function existsByTitle(array $titles)
    {
        $qb = $this->createQueryBuilder('g');
        $this->addWhereConditions($qb, $titles, 'g', 'title', true);

        return $qb
            ->getQuery()
            ->getResult();
    }

    private function addWhereConditions(QueryBuilder $qb, array $clauses, string $alias, string $column, bool $strict)
    {
        if (empty($clauses))
            throw new \LogicException();

        foreach ($clauses as $index => $clause) {
            $conditions[] = "$alias.$column LIKE :string$index";
            $qb->setParameter("string$index", $strict ? $clause : "%$clause%");
        }

        $qb->andWhere($qb->expr()->orX(...$conditions ?? []));
    }
}
