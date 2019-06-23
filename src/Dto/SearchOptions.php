<?php

namespace App\Dto;

class SearchOptions
{
    private $forumsIds;

    private $genres;

    private $genreIds;

    private $studio;

    private $studioIds;

    public function getForumIds()
    {
        return $this->forumsIds;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function getGenreIds()
    {
        return $this->genreIds;
    }

    public function getStudio()
    {
        return $this->studio;
    }

    public function getStudioIds()
    {
        return $this->studioIds;
    }
}
