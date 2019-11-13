<?php

namespace App\Domain\Service;

use App\Domain\Entity\Enum\ImageFormat;
use App\Domain\Entity\Topic;
use App\Domain\Factory\ImageFactory;
use App\Domain\Repository\ImageRepositoryInterface;

class ImageService
{
    private $imageFactory;
    private $imageRepository;

    public function __construct(ImageFactory $imageFactory, ImageRepositoryInterface $imageRepository)
    {
        $this->imageFactory = $imageFactory;
        $this->imageRepository = $imageRepository;
    }

    public function makePosterImage(Topic $topic, string $frontUrl)
    {
        $image = $this->imageFactory->make($topic, new ImageFormat(ImageFormat::POSTER), $frontUrl, null, null);

        $this->imageRepository->save($image);
    }

    public function makeUnderSpoilerImage(Topic $topic, string $frontUrl, ?string $reference)
    {
        // TODO:
    }
}
