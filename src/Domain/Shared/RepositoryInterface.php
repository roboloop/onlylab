<?php

namespace OnlyTracker\Domain\Shared;

interface RepositoryInterface
{
    public function find($id);

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);

    public function save($entity);

    public function saveMultiple(array $entities);

    public function delete($entity);

    public function deleteMultiple(array $entities);
}
