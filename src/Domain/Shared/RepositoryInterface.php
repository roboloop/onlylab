<?php

namespace App\Domain\Shared;

interface PersistenceRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);

    public function save($entity);
}
