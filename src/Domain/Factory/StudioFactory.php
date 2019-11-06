<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Enum\StudioStatus;
use App\Domain\Entity\Studio;
use App\Domain\Repository\StudioRepositoryInterface;
use App\Domain\Shared\DateTimeUtilInterface;

class StudioFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(StudioRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(string $url, StudioStatus $status)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Studio($id, $url, $status, $createdAt);
    }
}
