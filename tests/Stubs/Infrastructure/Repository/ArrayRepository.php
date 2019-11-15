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

        if ($orderBy) {
            $callbacks = [];
            foreach ($orderBy as $field => $order) {
                if ($order === 'ASC') {
                    $callbacks[] = function ($a, $b) use ($field) {
                        return $this->propertyAccessor->get($a, $field) <=> $this->propertyAccessor->get($b, $field);
                    };
                } else {
                    $callbacks[] = function ($a, $b) use ($field) {
                        return $this->propertyAccessor->get($b, $field) <=> $this->propertyAccessor->get($a, $field);
                    };
                }
            }

            $orderCallback = function ($a, $b) use ($callbacks) {
                foreach ($callbacks as $callback) {
                    $interResult = $callback($a, $b);
                    if ($interResult) {
                        return $interResult;
                    }
                }

                return 0;
            };

            usort($result, $orderCallback);
        }

        return $result;
    }

    public function findAll()
    {
        return array_values($this->repo);
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
