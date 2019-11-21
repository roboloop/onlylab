<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;

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
