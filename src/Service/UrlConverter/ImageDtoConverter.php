<?php

namespace App\Service\UrlConverter;

use App\Dto\ImageDto;

class ImageDtoConverter
{
    private $imageUrlConverter;

    public function __construct(ImageUrlConverter $imageUrlConverter)
    {
        $this->imageUrlConverter = $imageUrlConverter;
    }

    public function convert(ImageDto $imageDto)
    {
        if (null !== $imageDto->getDirectUrlOriginal()) {
            return;
        }

        // TODO: deal with it
        // $url = $imageDto->getUrlOriginal();
        $url = $imageDto->getUrlPreview();

        $newUrl = $this->imageUrlConverter->convert($url);

        $imageDto->setDirectUrlOriginal($newUrl);
    }

    public function convertMany(array $imageDtos)
    {
        foreach ($imageDtos as $imageDto) {
            $this->convert($imageDto);
        }
    }
}
