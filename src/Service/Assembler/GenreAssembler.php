<?php

namespace App\Service\Assembler;

use App\Entity\Genre;

class GenreAssembler
{
    public function makeMany(array $titles)
    {
        return array_map(function ($title) {
            return $this->make($title);
        }, $titles);
    }

    public function make(string $title)
    {
        return (new Genre())->setTitle($title);
    }
}
