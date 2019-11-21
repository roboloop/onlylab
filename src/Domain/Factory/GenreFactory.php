<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Repository\GenreRepositoryInterface;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;

class GenreFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(GenreRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(string $title, ?string $description, bool $isApproved)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Genre($id, $title, $description, $isApproved, $createdAt);
    }
}
