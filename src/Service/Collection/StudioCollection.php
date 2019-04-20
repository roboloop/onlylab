<?php

namespace App\Service\Collection;

use App\Repository\StudioRepository;
use App\Service\Transformer\ArrayTransformer;

class StudioCollection extends CollectionImg
{
    protected $field = 'url';

    public function __construct(ArrayTransformer $arrayTransformer, StudioRepository $studioRepository)
    {
        $this->collection = $arrayTransformer->setKeyFromSource($studioRepository->findAll(), $this->field);
    }
}
