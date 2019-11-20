<?php

namespace App\Domain\Shared;

interface RepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);

    public function save($entity);

    public function saveMultiple(array $entities);
}
