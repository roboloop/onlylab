<?php

namespace OnlyTracker\Application\CRUD;

use OnlyTracker\Application\Dto\RawForumDto;
use OnlyTracker\Application\Dto\RawImageDto;
use OnlyTracker\Application\Dto\RawTopicDto;
use OnlyTracker\Application\Exception\InvalidArgumentWhenCreatingTopicException;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\ForumService;
use OnlyTracker\Domain\Service\GenreService;
use OnlyTracker\Domain\Service\ImageService;
use OnlyTracker\Domain\Service\StudioService;
use OnlyTracker\Domain\Service\TopicService;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;
use OnlyTracker\Infrastructure\Util\Parser\Title\TitleParserManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TopicCreation
{
    private $validator;
    private $parserManager;
    private $genreService;
    private $studioService;
    private $forumService;
    private $topicService;
    private $imageService;
    private $dateTimeUtil;
    private $topicRepository;

    public function __construct(
        ValidatorInterface $validator,
        TitleParserManager $parserManager,
        GenreService $genreService,
        StudioService $studioService,
        ForumService $forumService,
        TopicService $topicService,
        ImageService $imageService,
        DateTimeUtilInterface $dateTimeUtil,
        TopicRepositoryInterface $topicRepository
    ) {
        $this->validator        = $validator;
        $this->parserManager    = $parserManager;
        $this->genreService     = $genreService;
        $this->studioService    = $studioService;
        $this->forumService     = $forumService;
        $this->topicService     = $topicService;
        $this->imageService     = $imageService;
        $this->dateTimeUtil     = $dateTimeUtil;
        $this->topicRepository  = $topicRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function createFromDto(RawTopicDto $dto): Topic
    {
        $this->validateRawTopicDto($dto);
        $this->convertTypesDto($dto);

        if ($topic = $this->topicRepository->find($dto->getExId())) {
            // TODO: copy old values, replace empty attributes on new entity by old values
            $topic->getImages();
            $this->topicRepository->delete($topic);
        }

        $rawGenres  = $this->parserManager->genres($dto->getRawTitle());
        $genres     = $this->genreService->getOrMakeOrBoth($rawGenres);

        $rawStudios = $this->parserManager->studios($dto->getRawTitle());
        $studios    = $this->studioService->getOrMakeOrBoth($rawStudios);

        $forum      = $this->forumService->getOrMake($dto->getForum()->getExId(), $dto->getForum()->getTitle());
        $topic      = $this->topicService->makeNotLoaded($dto->getExId(), $dto->getRawTitle(), $forum, $dto->getSize(), $dto->getExCreatedAt());

        $this->topicRepository->save($topic);

        /** @var \OnlyTracker\Application\Dto\RawImageDto $rawImageDto */
        foreach ($dto->getImages() as $rawImageDto) {
            $image = $rawImageDto->getPlace() === RawImageDto::PLACE_ON_PAGE
                ? $this->imageService->makePosterImage($topic, $rawImageDto->getFrontUrl())
                // TODO: what to do, when no spoiler name?
                : $this->imageService->makeUnderSpoilerImage($topic, $rawImageDto->getFrontUrl(), $rawImageDto->getReference(), $rawImageDto->getSpoilerName() ?? '');
            $topic->addImage($image);
        }

        foreach ($genres as $genre) {
            $topic->addGenre($genre);
        }

        foreach ($studios as $studio) {
            $topic->addStudio($studio);
        }

        return $topic;
    }

    private function validateRawTopicDto(RawTopicDto $dto)
    {
        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            throw new InvalidArgumentWhenCreatingTopicException;
        }
    }

    private function convertTypesDto(RawTopicDto &$dto)
    {
        $images = $dto->getImages();
        $forum = $dto->getForum();

        $dto = new RawTopicDto(
            $dto->getExId(),
            (string) $dto->getRawTitle(),
            $dto->getSize() ? Size::createFromString($dto->getSize()) : null,
            $dto->getExCreatedAt() ? $this->dateTimeUtil->ymdHi($dto->getExCreatedAt()) : null,
            new RawForumDto($forum->getExId(), $forum->getTitle()),
            $images
        );
    }
}
