<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Deduction\NoOriginalUrlDeductionSupportsException;
use OnlyTracker\Domain\Deduction\OriginalUrlDeductionInterface;
use OnlyTracker\Domain\Entity\Enum\ImageFormat;
use OnlyTracker\Domain\Entity\Image;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Factory\ImageFactory;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;

class ImageService
{
    private $imageFactory;
    private $imageRepository;
    private $urlDeduction;

    public function __construct(ImageRepositoryInterface $imageRepository, ImageFactory $imageFactory, OriginalUrlDeductionInterface $urlDeduction)
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
