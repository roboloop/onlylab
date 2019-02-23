<?php

namespace App\Service\Filter\Topic;

class GenreFilter
{
    private $preferable = [
        'anal',
    ];

    private $preferablePattern;

    public function __construct()
    {
        foreach ($this->preferable as $ban) {
            $this->preferablePattern[] = "~$ban~i";
        }
    }

    public function filter(string $genre)
    {
        preg_replace($this->preferablePattern, '', $genre, -1, $res);

        return $res >= 1;
    }
}
