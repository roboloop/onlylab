<?php

namespace App\Service\Assembler;

use App\Dto\ImageDto;
use App\Entity\Image;

class ImageAssembler
{
    public function make(ImageDto $dto)
    {
        $directUrlOriginal  = $this->sanitize($dto->getDirectUrlOriginal());
        $directUrlPreview   = $this->sanitize($dto->getDirectUrlPreview());
        $urlOriginal        = $this->sanitize($dto->getUrlOriginal());

        return (new Image())
            ->setType($dto->getType())
            ->setOriginal($directUrlOriginal)
            ->setPreview($directUrlPreview)
            ->setReference($urlOriginal);
    }

    protected function sanitize($string)
    {
        return empty($string) ? null : $string;
    }

    public function makeMany(array $imageDtos)
    {
        return array_map(function ($imageDto) {
            return $this->make($imageDto);
        }, $imageDtos);
    }
}
