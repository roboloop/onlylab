<?php

namespace App\Service;

use App\Assembler\GenreAssembler;
use App\Bag\Bag;
use App\Repository\GenreRepository;

class GenreService
{
    private $genreRepository;
    private $genreAssembler;
    private $titleParser;

    public function __construct(
        GenreRepository $genreRepository,
        GenreAssembler $genreAssembler,
        TitleParserService $titleParser)
    {
        $this->genreRepository  = $genreRepository;
        $this->genreAssembler   = $genreAssembler;
        $this->titleParser      = $titleParser;
    }

    public function getGenresFromTitles(array $titles)
    {
        $existsGenres = $this->genreRepository->existsByTitle($titles);
        $existsTitles = array_map(function ($genre) {
            /** @var \App\Entity\Genre $genre */
            return mb_strtolower($genre->getTitle());
        }, $existsGenres);

        $toCreate = array_filter($titles, function($title) use ($existsTitles) {
            return !in_array(mb_strtolower($title), $existsTitles, true);
        });

        $newGenres = $this->genreAssembler->make($toCreate);

        return array_merge($existsGenres, $newGenres);
    }

    public function getGenres(Bag $topicBag)
    {
        return $this->titleParser->getGenres($topicBag['title']);
    }
}
