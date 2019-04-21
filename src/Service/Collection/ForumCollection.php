<?php

namespace App\Service\Collection;

use App\Repository\ForumRepository;
use App\Service\Transformer\ArrayTransformer;

class ForumCollection extends CollectionImg
{
    protected $field = 'trackerId';

    public function __construct(ArrayTransformer $arrayTransformer, ForumRepository $forumRepository)
    {
        $this->collection = $arrayTransformer->setKeyFromSource($forumRepository->findAll(), $this->field);
    }
}
