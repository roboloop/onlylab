<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Factory\GenreFactory;
use OnlyTracker\Domain\Repository\GenreRepositoryInterface;

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
     * @return \OnlyTracker\Domain\Entity\Genre[]
     */
    public function getOrMakeOrBoth(array $titles)
    {
        $titles = $this->filterTitles($titles);

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

        $this->genreRepository->saveMultiple($newGenres);

        return array_values(array_merge($genres, $newGenres));
    }

    public function approve(Genre $genre)
    {
        $genre->approve();

        $this->genreRepository->save($genre);
    }

    public function disapprove(Genre $genre)
    {
        $genre->disapprove();

        $this->genreRepository->save($genre);
    }

    /**
     * @param null|string $title
     * @param bool|null   $isApproved
     *
     * @return \OnlyTracker\Domain\Entity\Genre[]
     */
    public function search(?string $title, ?bool $isApproved)
    {
        $criteria = [];

        if (null !== $title) {
            $criteria['title'] = "%$title%";
        }

        if (null !== $isApproved) {
            $criteria['isApproved'] = $isApproved;
        }

        return $this->genreRepository->findBy($criteria, ['title' => 'ASC']);
    }

    private function filterTitles(array $titles)
    {
        return array_values(
            array_intersect_key(
                $titles,
                array_unique(
                    array_map('mb_strtolower', $titles)
                )
            )
        );
    }
}
