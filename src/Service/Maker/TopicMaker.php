<?php

namespace App\Service\Maker;

use App\Dto\RawTopicDto;
use App\Entity\Topic;
use App\Service\Assembler\ForumAssembler;
use App\Service\Assembler\GenreAssembler;
use App\Service\Assembler\ImageAssembler;
use App\Service\Assembler\StudioAssembler;
use App\Service\Assembler\TopicAssembler;
use App\Service\Collection\ForumCollection;
use App\Service\Collection\GenreCollection;
use App\Service\Collection\StudioCollection;
use App\Service\Processor\TitleProcessor;
use App\Service\Transformer\ArrayTransformer;
use App\Service\UrlConverter\ImageDtoConverter;
use App\Service\UrlConverter\ImageUrlConverter;

class TopicMaker
{
    private $titleProcessor;
    private $genreAssembler;
    private $studioAssembler;
    private $topicAssembler;
    private $forumAssembler;
    private $arrayTransformer;
    private $genreCollection;
    private $studioCollection;
    private $imageAssembler;
    private $imageDtoConverter;
    private $forumCollection;
    private $imageUrlConverter;

    public function __construct(
        TitleProcessor $titleProcessor,
        GenreAssembler $genreAssembler,
        StudioAssembler $studioAssembler,
        TopicAssembler $topicAssembler,
        ImageAssembler $imageAssembler,
        ForumAssembler $forumAssembler,
        ArrayTransformer $arrayTransformer,
        GenreCollection $genreCollection,
        StudioCollection $studioCollection,
        ForumCollection $forumCollection,
        ImageDtoConverter $imageDtoConverter,
        ImageUrlConverter $imageUrlConverter
    ) {
        $this->titleProcessor   = $titleProcessor;
        $this->genreAssembler   = $genreAssembler;
        $this->studioAssembler  = $studioAssembler;
        $this->topicAssembler   = $topicAssembler;
        $this->forumAssembler   = $forumAssembler;
        $this->arrayTransformer = $arrayTransformer;
        $this->genreCollection  = $genreCollection;
        $this->studioCollection = $studioCollection;
        $this->imageAssembler   = $imageAssembler;
        $this->forumCollection  = $forumCollection;
        $this->imageDtoConverter = $imageDtoConverter;
        $this->imageUrlConverter = $imageUrlConverter;
    }

    /**
     * Create a topic from raw values, existing genres and studios are passed on for processing
     *
     * @param \App\Dto\RawTopicDto $dto
     *
     * @return \App\Entity\Topic
     */
    public function makeTopic(RawTopicDto $dto)
    {
        // Collecting genres
        $rawGenres      = $this->titleProcessor->rawGenresFromTitle($dto->getRawTitle());
        $existsGenres   = $this->genreCollection->intersectWithRawData($rawGenres);
        $newGenres      = $this->genreAssembler->makeMany($rawGenres);
        $genres         = array_merge($existsGenres, $newGenres);

        // Collecting studious
        $rawStudios     = $this->titleProcessor->rawStudiosFromTitle($dto->getRawTitle());
        $existsStudios  = $this->studioCollection->intersectWithRawData($rawStudios);
        $newStudios     = $this->studioAssembler->makeMany($rawStudios);
        $studios        = array_merge($existsStudios, $newStudios);

        // Filtering images
        $this->imageDtoConverter->convertMany($dto->getImages());

        // Building images
        $rawImages = $dto->getImages();
        $this->postHandleImages($rawImages);
        $images = $this->imageAssembler->makeMany($rawImages);

        // Forum
        $forum = $this->getForum($dto->getForumId(), $dto->getForumTitle());

        // Building object
        $topic = $this->topicAssembler->make($dto);

        // Adding associations
        $this->addGenres($topic, $genres);
        $this->addStudios($topic, $studios);
        $this->addImages($topic, $images);
        $topic->setForum($forum);

        $this->genreCollection->addMany($newGenres);
        $this->studioCollection->addMany($newStudios);
        $this->forumCollection->add($forum);

        return $topic;
    }

    private function getForum($forumId, $forumTitle)
    {
        if (null === $forumId) {
            return null;
        }

        return $this->forumCollection->isExists($forumId)
            ? $this->forumCollection->get($forumId)
            : $this->forumAssembler->make($forumId, $forumTitle);
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

    private function addImages(Topic $topic, array $images)
    {
        array_walk($images, function ($image) use ($topic) {
            $topic->addImage($image);
        });
    }

    private function postHandleImages($images)
    {
        array_walk($images, function ($imageDto) {
            /** @var $imageDto \App\Dto\ImageDto */
            if (null !== $imageDto->getDirectUrlOriginal()) {
                return;
            }
            $urlPreview = $imageDto->getDirectUrlPreview();

            $directUrlOriginal = $this->imageUrlConverter->convert($urlPreview);

            $imageDto->setDirectUrlOriginal($directUrlOriginal);
        });
    }
}
