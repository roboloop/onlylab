<?php

namespace OnlyTracker\Shared\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use OnlyTracker\Domain\Shared\RepositoryInterface;

abstract class DoctrineRepository implements RepositoryInterface
{
    private $basicRepository;

    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;
    protected $entityClass;

    public function __construct(EntityManagerInterface $entityManager, string $entityClass)
    {
        $this->entityClass      = $entityClass;
        $this->entityManager    = $entityManager;
        $this->basicRepository  = $this->entityManager->getRepository($entityClass);
    }

    public function find($id)
    {
        return $this->basicRepository->find($id);
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->basicRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
    }

    public function saveMultiple(array $entities)
    {
        foreach ($entities as $entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush($entities);
    }

    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);
    }

    public function deleteMultiple(array $entities)
    {
        foreach ($entities as $entity) {
            $this->entityManager->remove($entity);
        }

        $this->entityManager->flush($entities);
    }

    protected function orLikeExpr(array $values, string $field, $type = null)
    {
        $prefix = str_replace('.', '_', $field);

        $params = $args = $orLike = [];
        for ($i = 0; $i < count($values); $i++) {
            $params[]   = $param = $prefix . $i;
            $orLike[]   = "$field LIKE :$param";
            $args[]     = new Parameter($param, '%' . $values[$i] . '%', $type);
        }

        $orLike = implode(' OR ', $orLike);

        return [$orLike, $params, $args];
    }

    protected function andWhere(QueryBuilder $qb, string $predicate, array $params, array $args)
    {
        $qb->andWhere($predicate);
        for ($i = 0; $i < count($params); $i++) {
            $qb->setParameter($params[$i], $args[$i]);
        }
    }
}
