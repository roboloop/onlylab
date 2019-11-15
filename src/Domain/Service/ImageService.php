<?php

namespace App\Domain\Service;

use App\Domain\Deduction\NoOriginalUrlDeductionSupportsException;
use App\Domain\Deduction\OriginalUrlDeductionInterface;
use App\Domain\Entity\Enum\ImageFormat;
use App\Domain\Entity\Image;
use App\Domain\Entity\Topic;
use App\Domain\Factory\ImageFactory;
use App\Domain\Repository\ImageRepositoryInterface;

class ImageService
{
    private $imageFactory;
    private $imageRepository;
    private $urlDeduction;

    public function __construct(ImageFactory $imageFactory, ImageRepositoryInterface $imageRepository, OriginalUrlDeductionInterface $urlDeduction)
    {
        $this->imageFactory     = $imageFactory;
        $this->imageRepository  = $imageRepository;
        $this->urlDeduction     = $urlDeduction;
    }

    public function makePosterImage(Topic $topic, string $frontUrl): Image
    {
        $image = $this->imageFactory->make($topic, new ImageFormat(ImageFormat::POSTER), $frontUrl, null, null);

        $this->imageRepository->save($image);

        return $image;
    }

    public function makeUnderSpoilerImage(Topic $topic, string $frontUrl, ?string $reference, string $spoilerName): Image
    {
        try {
            $original = $this->urlDeduction->deduct($frontUrl, ['reference' => $reference]);
        } catch (NoOriginalUrlDeductionSupportsException $e) {
            $original = null;
        }

        $format = ImageFormat::createFromSpoilerName($spoilerName);
        $image  = $this->imageFactory->make($topic, $format, $frontUrl, $reference, $original);

        $this->imageRepository->save($image);

        return $image;
    }
}
