<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Enum\ImageFormat;
use OnlyTracker\Domain\Entity\Image;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;

class ImageFactory
{
    private $repository;
    private $dateTimeUtil;

    public function __construct(ImageRepositoryInterface $repository, DateTimeUtilInterface $dateTimeUtil)
    {
        $this->repository   = $repository;
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(Topic $topic, ImageFormat $format, string $frontUrl, ?string $reference, ?string $original)
    {
        $id         = $this->repository->nextIdentity();
        $createdAt  = $this->dateTimeUtil->now();

        return new Image($id, $topic, $format, $frontUrl, $reference, $original, $createdAt);
    }
}
