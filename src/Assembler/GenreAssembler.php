<?php

namespace App\Assembler;

use App\Entity\Genre;

class GenreAssembler
{
    public function make(array $titles)
    {
        return array_map(function ($title) {
            return (new Genre())->setTitle($title);
        }, $titles);
    }
}
