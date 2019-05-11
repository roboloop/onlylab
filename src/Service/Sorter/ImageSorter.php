<?php

namespace App\Service\Sorter;

use App\Constant\ImageType;

class ImageSorter
{
    public function sort(array $images)
    {
        $grouped = [];
        $sorted  = [];

        /** @var \App\Entity\Image $image */
        foreach ($images as $image) {
            $type = $image->getType();

            $grouped[$type][] = $image;
        }

        foreach ($this->order() as $imageType) {
            $sorted[$imageType] = $grouped[$imageType] ?? [];
        }

        return array_merge(...$sorted);
    }

    private function order()
    {
        return [
            ImageType::POSTER,
            ImageType::SCREENSHOT,
            ImageType::SCREENLISTING,
            ImageType::GIF,
            ImageType::OTHER
        ];
    }
}
