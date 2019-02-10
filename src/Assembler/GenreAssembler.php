<?php

namespace App\Assembler;

use App\Bag\Bag;
use App\Entity\Genre;
use App\Validator\GenreBagValidator;

class GenreAssembler
{
    /** @var \App\Validator\GenreBagValidator */
    private $bagValidator;

    public function __construct(GenreBagValidator $bagValidator)
    {
        $this->bagValidator = $bagValidator;
    }

    public function make(Bag $bag)
    {
        $this->bagValidator->validate($bag);

        $genres = [];

        foreach ($bag->all() as $genreBag) {
            $genres[] = (new Genre())
                ->setTitle($genreBag['title'])
                ->setStatus($genreBag['status']);
        }

        return $genres;
    }
}
