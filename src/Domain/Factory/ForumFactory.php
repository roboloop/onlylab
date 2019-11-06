<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Forum;
use App\Domain\Repository\ForumRepositoryInterface;
use App\Domain\Shared\DateTimeUtilInterface;

class ForumFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(ForumRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(int $exId, string $title)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Forum($id, $exId, $title, $createdAt);
    }
}
