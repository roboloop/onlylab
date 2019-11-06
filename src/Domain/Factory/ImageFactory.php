<?php

namespace App\Domain\Factory;

use App\Domain\Entity\Enum\ImageFormat;
use App\Domain\Entity\Image;
use App\Domain\Entity\Topic;
use App\Domain\Repository\ImageRepositoryInterface;
use App\Domain\Shared\DateTimeUtilInterface;

class ImageFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(ImageRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(Topic $topic, ImageFormat $format, ?string $preview, ?string $reference, ?string $original, ?string $host)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Image($id, $topic, $format, $preview, $reference, $original, $host, $createdAt);
    }
}
