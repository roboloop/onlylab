<?php

namespace OnlyTracker\Application\Creator;

use OnlyTracker\Application\Dto\RawImageDto;
use OnlyTracker\Application\Dto\RawTopicDto;
use OnlyTracker\Application\Exception\InvalidArgumentWhenCreatingTopicException;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\ForumService;
use OnlyTracker\Domain\Service\GenreService;
use OnlyTracker\Domain\Service\ImageService;
use OnlyTracker\Domain\Service\StudioService;
use OnlyTracker\Domain\Service\TopicService;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;
use OnlyTracker\Infrastructure\Util\Parser\Title\TitleParserManager;
use OnlyTracker\Infrastructure\Util\SizeConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TopicCreator
{
    private $validator;
    private $parserManager;
    private $genreService;
    private $studioService;
    private $forumService;
    private $topicService;
    private $imageService;
    private $dateTimeUtil;
    private $sizeConverter;
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
        SizeConverter $sizeConverter,
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
        $this->sizeConverter    = $sizeConverter;
        $this->topicRepository  = $topicRepository;
    }

    public function createFromDto(RawTopicDto $dto)
    {
        $this->validateRawTopicDto($dto);
        $this->convertTypesDto($dto);

        $rawGenres  = $this->parserManager->genres($dto->getRawTitle());
        $genres     = $this->genreService->getOrMakeOrBoth($rawGenres);

        $rawStudios = $this->parserManager->studios($dto->getRawTitle());
        $studios    = $this->studioService->getOrMakeOrBoth($rawStudios);

        $forum      = $this->forumService->getOrMake($dto->getForum()->getExId(), $dto->getForum()->getTitle());
        $topic      = $this->topicService->makeNotLoaded($dto->getExId(), $dto->getRawTitle(), $forum, $dto->getSize(), $dto->getExCreatedAt());

        /** @var \OnlyTracker\Application\Dto\RawImageDto $image */
        foreach ($dto->getImages() as $image) {
            $image->getPlace() === RawImageDto::PLACE_ON_PAGE
                ? $this->imageService->makePosterImage($topic, $image->getFrontUrl())
                : $this->imageService->makeUnderSpoilerImage($topic, $image->getFrontUrl(), $image->getReference(), $image->getSpoilerName());
        }

        foreach ($genres as $genre) {
            $topic->addGenre($genre);
        }

        foreach ($studios as $studio) {
            $topic->addStudio($studio);
        }

        $this->topicRepository->save($topic);

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

        $dto = new RawTopicDto(
            (int) $dto->getExId(),
            (string) $dto->getRawTitle(),
            $this->sizeConverter->fromStringToInt($dto->getSize()),
            $this->dateTimeUtil->ymdHi($dto->getExCreatedAt()),
            $dto->getForum(),
            $images
        );
    }
}
