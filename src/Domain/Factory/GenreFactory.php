<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepositoryInterface;
use App\Domain\Shared\DateTimeUtilInterface;

class GenreFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(GenreRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(string $title, string $description, bool $isApproved)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Genre($id, $title, $description, $isApproved, $createdAt);
    }
}
