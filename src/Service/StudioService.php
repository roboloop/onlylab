<?php

namespace App\Service;

use App\Repository\StudioRepository;

class StudioService
{
    private $studioRepository;

    public function __construct(StudioRepository $studioRepository)
    {
        $this->studioRepository = $studioRepository;
    }

    public function findAll()
    {
        return $this->studioRepository->findAll();
    }
}
