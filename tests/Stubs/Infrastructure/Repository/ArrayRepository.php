<?php

namespace App\Tests\Stubs\Infrastructure\Repository;

use App\Domain\Shared\RepositoryInterface;
use App\Tests\Helpers\PropertyAccessor;
use Ramsey\Uuid\Uuid;

class ArrayRepository implements RepositoryInterface
{
    protected $repo = [];
    protected $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = new PropertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $result = $this->repo;

        foreach ($criteria as $field => $criterion) {
            $criterion = is_array($criterion) ? $criterion : [$criterion];
            foreach ($result as $key => $item) {
                if (!in_array($this->propertyAccessor->get($item, $field), $criterion, true)) {
                    unset($result[$key]);
                }
            }
        }

        return $result;
    }

    public function findAll()
    {
        return $this->repo;
    }

    /**
     * {@inheritdoc}
     */
    public function save($entity)
    {
        $entities = is_array($entity) ? $entity : [$entity];

        /** @var \App\Domain\Shared\UniqueIdentityInterface $entity */
        foreach ($entities as $entity) {
            $this->repo[$entity->getId()] = $entity;
        }
    }

    public function nextIdentity()
    {
        return Uuid::uuid4()->toString();
    }
}
