<?php

namespace App\Domain\Service;

use App\Domain\Factory\GenreFactory;
use App\Domain\Repository\GenreRepositoryInterface;

class GenreService
{
    private $genreRepository;
    private $genreFactory;

    public function __construct(GenreRepositoryInterface $genreRepository, GenreFactory $genreFactory)
    {
        $this->genreRepository  = $genreRepository;
        $this->genreFactory     = $genreFactory;
    }

    /**
     * @param string[] $titles
     *
     * @return \App\Domain\Entity\Genre[]
     */
    public function getOrMakeOrBoth(array $titles)
    {
        $repositoryGenres = $newGenres = [];

        $genres = $this->genreRepository->findBy(['title' => $titles]);

        foreach ($genres as $genre) {
            $repositoryGenres[] = $genre->getTitle();
        }

        $newRawGenres = array_udiff($titles, $repositoryGenres, function ($title1, $title2) {
            return mb_strtolower($title1) <=> mb_strtolower($title2);
        });

        foreach ($newRawGenres as $newRawGenre) {
            $newGenres[] = $this->genreFactory->make($newRawGenre, null, false);
        }

        $this->genreRepository->save($newGenres);

        return array_merge($genres, $newGenres);
    }
}
