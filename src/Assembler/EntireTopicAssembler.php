<?php

namespace App\Assembler;

use App\Bag\Bag;
use App\Entity\Topic;
use App\Service\GenreService;
use App\Service\StudioService;

class EntireTopicAssembler
{
    private $topicAssembler;
    private $imageAssembler;
    private $genreAssembler;
    private $genreService;
    /** @var \App\Service\StudioService */
    private $studioService;

    public function __construct(
        TopicAssembler $topicAssembler,
        ImageAssembler $imageAssembler,
        GenreAssembler $genreAssembler,
        StudioService $studioService,
        GenreService $genreService)
    {
        $this->topicAssembler = $topicAssembler;
        $this->imageAssembler = $imageAssembler;
        $this->genreAssembler = $genreAssembler;
        $this->studioService  = $studioService;
        $this->genreService   = $genreService;
    }

    public function makeEntire(Bag $topicBag, Bag $imagesBag)
    {
        $genres = $this->genreService->getGenresFromTitles(
            $this->genreService->getGenres($topicBag)
        );
        $studios = $this->studioService->getStudiosFromUrls(
            $this->studioService->getStudios($topicBag)
        );
        $topic  = $this->topicAssembler->make($topicBag);
        $images = $this->imageAssembler->make($imagesBag);

        $this->addImages($topic, $images);
        $this->addGenres($topic, $genres);
        $this->addStudios($topic, $studios);

        return $topic;
    }

    public function makeReview(Bag $topicBag)
    {
        $genres = $this->genreService->getGenresFromTitles(
            $this->genreService->getGenres($topicBag)
        );
        $studios = $this->studioService->getStudiosFromUrls(
            $this->studioService->getStudios($topicBag)
        );
        $topic  = $this->topicAssembler->make($topicBag);

        $this->addGenres($topic, $genres);
        $this->addStudios($topic, $studios);

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

    private function addStudios(Topic $topic, array $studios)
    {
        array_walk($studios, function ($studio) use ($topic) {
            $topic->addStudio($studio);
        });
    }
}
