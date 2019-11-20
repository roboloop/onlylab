<?php

namespace App\Shared\Infrastructure;

use App\Domain\Shared\RepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Common\Persistence\ManagerRegistry;
use LogicException;

abstract class DoctrineRepository implements RepositoryInterface
{
    private $basicRepository;

    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;
    protected $entityClass;

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        $this->entityClass   = $entityClass;
        $this->entityManager = $registry->getManagerForClass($entityClass);

        if (null === $this->entityManager) {
            throw new LogicException('No entity manager to class ' . $entityClass);
        }

        $this->basicRepository = $this->entityManager->getRepository($entityClass);
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

    public function nextIdentity()
    {
        return Uuid::random();
    }
}
