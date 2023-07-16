<?php

namespace OnlyTracker\Application\CRUD;

use OnlyTracker\Application\Dto\RawForumDto;
use OnlyTracker\Application\Dto\RawImageDto;
use OnlyTracker\Application\Dto\RawTopicDto;
use OnlyTracker\Application\Exception\InvalidArgumentWhenCreatingTopicException;
use OnlyTracker\Domain\Entity\Enum\ImageFormat;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\ForumService;
use OnlyTracker\Domain\Service\GenreService;
use OnlyTracker\Domain\Service\ImageService;
use OnlyTracker\Domain\Service\StudioService;
use OnlyTracker\Domain\Service\TopicDeletion;
use OnlyTracker\Domain\Service\TopicService;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;
use OnlyTracker\Infrastructure\Util\Parser\Title\TitleParserManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TopicCreation
{
    private const MAX_IMAGES = 20;
    private $validator;
    private $parserManager;
    private $genreService;
    private $studioService;
    private $forumService;
    private $topicService;
    private $imageService;
    private $dateTimeUtil;
    private $topicRepository;
    private $topicDeletion;

    public function __construct(
        ValidatorInterface $validator,
        TitleParserManager $parserManager,
        GenreService $genreService,
        StudioService $studioService,
        ForumService $forumService,
        TopicService $topicService,
        ImageService $imageService,
        DateTimeUtilInterface $dateTimeUtil,
        TopicRepositoryInterface $topicRepository,
        TopicDeletion $topicDeletion
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
        $this->topicDeletion    = $topicDeletion;
    }

    /**
     * {@inheritDoc}
     */
    public function createFromDto(RawTopicDto $dto): Topic
    {
        $this->validateRawTopicDto($dto);
        $this->convertTypesDto($dto);

        $oldTopic = $this->topicRepository->find($dto->getExId());
        if (null !== $oldTopic) {
            $this->topicDeletion->delete($dto->getExId());
            $size = $oldTopic->getSize();
            $exCreatedAt = $oldTopic->getExCreatedAt();
        }

        $rawGenres  = $this->parserManager->genres($dto->getRawTitle());
        $genres     = $this->genreService->getOrMakeOrBoth($rawGenres);

        $rawStudios = $this->parserManager->studios($dto->getRawTitle());
        $studios    = $this->studioService->getOrMakeOrBoth($rawStudios);

        $forum      = $this->forumService->getOrMake($dto->getForum()->getExId(), $dto->getForum()->getTitle());
        $topic      = $this->topicService->makeNotLoaded($dto->getExId(), $dto->getRawTitle(), $forum, $size ?? $dto->getSize(), $exCreatedAt ?? $dto->getExCreatedAt());

        $this->topicRepository->save($topic);

        /** @var \OnlyTracker\Application\Dto\RawImageDto[] $rawImageDtos */
        $rawImageDtos = $dto->getImages();
        if (count($rawImageDtos) > self::MAX_IMAGES) {
            shuffle($rawImageDtos);
            $rawImageDtos = array_slice($rawImageDtos, 0, self::MAX_IMAGES);
        }

        foreach ($rawImageDtos as $rawImageDto) {
            // Ignore the upload of photosets
            if (ImageFormat::isPhotoset($rawImageDto->getSpoilerName() ?? '')) {
                continue;
            }

            $image = $rawImageDto->getPlace() === RawImageDto::PLACE_ON_PAGE
                ? $this->imageService->makePosterImage($topic, $rawImageDto->getFrontUrl())
                // TODO: what to do, when no spoiler name?
                : $this->imageService->makeUnderSpoilerImage($topic, $rawImageDto->getFrontUrl(), $rawImageDto->getReference(), $rawImageDto->getSpoilerName() ?? '');
            $topic->addImage($image);
        }


        // If cartoons category (hard to parse, keep title)
        if ($forum->getId() === '8510') {
            $dummyGenre = $this->genreService->getOrMakeDummy();
            $dummyStudio = $this->studioService->getOrMakeDummy();

            $topic->addGenre($dummyGenre);
            $topic->addStudio($dummyStudio);
        } else {
            foreach ($genres as $genre) {
                $topic->addGenre($genre);
            }

            foreach ($studios as $studio) {
                $topic->addStudio($studio);
            }
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
