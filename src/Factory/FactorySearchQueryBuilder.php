<?php

namespace App\Factory;

use App\QueryBuilder\SearchQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class FactorySearchQueryBuilder
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function make()
    {
        return new SearchQueryBuilder($this->entityManager);
    }
}
