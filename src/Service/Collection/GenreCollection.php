<?php

namespace App\Service\Collection;

use App\Repository\GenreRepository;
use App\Service\Transformer\ArrayTransformer;

class GenreCollection extends CollectionImg
{
    protected $field = 'title';

    public function __construct(ArrayTransformer $arrayTransformer, GenreRepository $genreRepository)
    {
        $this->collection = $arrayTransformer->setKeyFromSource($genreRepository->findAll(), $this->field);
    }
}
