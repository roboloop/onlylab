<?php

namespace App\Service\Maker;

use App\Dto\ForumLineDto;
use App\Entity\Topic;
use App\Service\Assembler\GenreAssembler;
use App\Service\Assembler\StudioAssembler;
use App\Service\Assembler\TopicAssembler;
use App\Service\Processor\TitleProcessor;
use App\Service\Transformer\ArrayTransformer;

class TopicMaker
{
    private $titleProcessor;
    private $genreAssembler;
    private $studioAssembler;
    private $topicAssembler;
    private $arrayTransformer;

    public function __construct(
        TitleProcessor $titleProcessor,
        GenreAssembler $genreAssembler,
        StudioAssembler $studioAssembler,
        TopicAssembler $topicAssembler,
        ArrayTransformer $arrayTransformer
    ) {
        $this->titleProcessor   = $titleProcessor;
        $this->genreAssembler   = $genreAssembler;
        $this->studioAssembler  = $studioAssembler;
        $this->topicAssembler   = $topicAssembler;
        $this->arrayTransformer = $arrayTransformer;
    }

    /**
     * Create a topic from raw values, existing genres and studios are passed on for processing
     *
     * @param \App\Dto\ForumLineDto $dto
     * @param array                 $allGenres
     * @param array                 $allStudios
     *
     * @return \App\Entity\Topic
     */
    public function makeTopic(ForumLineDto $dto, array $allGenres, array $allStudios)
    {
        $rawGenres      = $this->titleProcessor->rawGenresFromTitle($dto->getTitle());
        $existsGenres   = $this->titleProcessor->existsFromRaw($allGenres, $rawGenres);
        $newGenres      = $this->genreAssembler->makeMany($rawGenres);
        $genres         = array_merge($existsGenres, $newGenres);

        $rawStudios     = $this->titleProcessor->rawStudiosFromTitle($dto->getTitle());
        $existsStudios  = $this->titleProcessor->existsFromRaw($allStudios, $rawStudios);
        $newStudios     = $this->studioAssembler->makeMany($rawStudios);
        $studios        = array_merge($existsStudios, $newStudios);

        $topic = $this->topicAssembler->make($dto);

        $this->addGenres($topic, $genres);
        $this->addStudios($topic, $studios);

        return $topic;
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
