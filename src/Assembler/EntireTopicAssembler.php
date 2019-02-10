<?php

namespace App\Assembler;

use App\Bag\Bag;
use App\Entity\Topic;
use App\Service\GenreService;

class EntireTopicAssembler
{
    private $topicAssembler;
    private $imageAssembler;
    private $genreAssembler;
    private $genreService;

    public function __construct(
        TopicAssembler $topicAssembler,
        ImageAssembler $imageAssembler,
        GenreAssembler $genreAssembler,
        GenreService $genreService)
    {
        $this->topicAssembler = $topicAssembler;
        $this->imageAssembler = $imageAssembler;
        $this->genreAssembler = $genreAssembler;
        $this->genreService   = $genreService;
    }

    public function makeEntire(Bag $topicBag, Bag $imagesBag)
    {
        $genres = $this->genreService->getGenresFromTitles(
            $this->genreService->getGenres($topicBag)
        );
        $topic  = $this->topicAssembler->make($topicBag);
        $images = $this->imageAssembler->make($imagesBag);

        $this->addImages($topic, $images);
        $this->addGenres($topic, $genres);

        return $topic;
    }

    public function makeReview(Bag $topicBag)
    {
        $genres = $this->genreService->getGenresFromTitles(
            $this->genreService->getGenres($topicBag)
        );
        $topic  = $this->topicAssembler->make($topicBag);

        $this->addGenres($topic, $genres);

        return $topic;
    }

    private function addImages(Topic $topic, array $images)
    {
        array_walk($images, function ($image) use ($topic) {
            $topic->addImage($image);
        });
    }

    private function addGenres(Topic $topic, array $genres)
    {
        array_walk($genres, function ($genre) use ($topic) {
            $topic->addGenre($genre);
        });
    }
}
