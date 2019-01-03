<?php

namespace App\Service;

use App\Filter\GenreFilter;
use App\Filter\SiteFilter;

class Filter
{
    /** @var \App\Filter\SiteFilter */
    private $siteFilter;

    /** @var \App\Filter\GenreFilter */
    private $genreFilter;

    public function __construct(SiteFilter $siteFilter, GenreFilter $genreFilter)
    {
        $this->siteFilter = $siteFilter;
        $this->genreFilter = $genreFilter;
    }

    /**
     * @param \App\Entity\Film[] $films
     *
     * @return array
     */
    public function getFiltered(array $films): array
    {
        $result = [];

        foreach ($films as $film) {
            if (! $this->siteFilter->filter($film->getSite()) and $this->genreFilter->filter($film->getGenre()))
                $result[] = $film;
        }

        return $result;
    }
}
