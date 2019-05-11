<?php

namespace App\Service\Sorter;

use App\Entity\Genre;

class GenreSorter
{
    public function sort(array $genres)
    {
        // A-Z
        usort($genres, [$this, 'compare']);

        return $genres;
    }

    private function compare(Genre $a, Genre $b)
    {
        return mb_strcasecmp($a->getTitle(), $b->getTitle());
    }
}
