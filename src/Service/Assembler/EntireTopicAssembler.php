<?php

namespace App\Service\Assembler;

use App\Bag\Bag;
use App\Dto\RawTopicDto;
use App\Entity\Topic;
use App\Service\GenreService;
use App\Service\StudioService;

class EntireTopicAssembler
{
    private $topicAssembler;
    private $imageAssembler;
    private $genreAssembler;
    private $genreService;
    private $studioService;

    public function __construct(
        TopicAssembler $topicAssembler,
        ImageAssembler $imageAssembler,
        GenreAssembler $genreAssembler,
        StudioService $studioService,
        GenreService $genreService
    ) {
        $this->topicAssembler = $topicAssembler;
        $this->imageAssembler = $imageAssembler;
        $this->genreAssembler = $genreAssembler;
        $this->studioService  = $studioService;
        $this->genreService   = $genreService;
    }

    public function makeEntire(RawTopicDto $dto, Bag $imagesBag)
    {
        $genres     = $this->genreService->genresFromTitle($dto->getRawTitle());
        $studios    = $this->studioService->studiosFromTitle($dto->getRawTitle());
        $topic      = $this->topicAssembler->make($dto);
        $images     = $this->imageAssembler->make($imagesBag);

        $this->addImages($topic, $images);
        $this->addGenres($topic, $genres);
        $this->addStudios($topic, $studios);

        return $topic;
    }

    public function makeReview(RawTopicDto $dto)
    {
        $genres     = $this->genreService->genresFromTitle($dto->getRawTitle());
        $studios    = $this->studioService->studiosFromTitle($dto->getRawTitle());
        $topic      = $this->topicAssembler->make($dto);

        $this->addGenres($topic, $genres);
        $this->addStudios($topic, $studios);

        return $topic;
    }

    public function makeManyReviews(array $dtos)
    {
        return array_map(function ($dto) {
            return $this->makeReview($dto);
        }, $dtos);
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
