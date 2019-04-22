<?php

namespace App\Service;

use App\Repository\GenreRepository;

class GenreService
{
    private $genreRepository;

    public function __construct(GenreRepository $genreRepository)
    {
        $this->genreRepository  = $genreRepository;
    }

    public function findAll()
    {
        return $this->genreRepository->findAll();
    }

    public function genresWithTopics()
    {
        return $this->genreRepository->genresWithTopics();
    }
}
