<?php

namespace App\Service\Assembler;

use App\Bag\Bag;
use App\Entity\Image;
use App\Validator\ImageBagValidator;

class ImageAssembler
{
    /** @var \App\Validator\ImageBagValidator */
    private $bagValidator;

    public function __construct(ImageBagValidator $bagValidator)
    {
        $this->bagValidator = $bagValidator;
    }

    public function make(Bag $bag)
    {
        $this->bagValidator->validate($bag);

        $images = [];

        foreach ($bag->all() as $imageBag) {
            $images[] = (new Image())
                ->setType($imageBag['type'])
                ->setPreview($imageBag['preview'])
                ->setReference($imageBag['reference'])
                ->setOriginal($imageBag['original'])
                ->setHost($imageBag['host']);
        }

        return $images;
    }
}
