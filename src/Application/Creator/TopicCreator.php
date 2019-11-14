<?php

namespace App\Application\Creator;

use App\Application\Dto\RawImageDto;
use App\Application\Dto\RawTopicDto;
use App\Application\Exception\InvalidArgumentWhenCreatingTopicException;
use App\Domain\Service\ForumService;
use App\Domain\Service\GenreService;
use App\Domain\Service\ImageService;
use App\Domain\Service\StudioService;
use App\Domain\Service\TopicService;
use App\Domain\Shared\DateTimeUtilInterface;
use App\Infrastructure\Util\Parser\Title\TitleParserManager;
use App\Infrastructure\Util\SizeConverter;
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

    public function __construct(
        ValidatorInterface $validator,
        TitleParserManager $parserManager,
        GenreService $genreService,
        StudioService $studioService,
        ForumService $forumService,
        TopicService $topicService,
        ImageService $imageService,
        DateTimeUtilInterface $dateTimeUtil,
        SizeConverter $sizeConverter
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
    }

    public function createFromDto(RawTopicDto $dto)
    {
        $this->validateRawTopicDto($dto);
        $this->convertTypesDto($dto);

        $rawGenres  = $this->parserManager->genres($dto->getRawTitle());
        $genres     = $this->genreService->getOrMakeOrBoth($rawGenres);

        $rawStudios = $this->parserManager->studios($dto->getRawTitle());
        $studios    = $this->studioService->getOrMakeOrBoth($rawStudios);

        $forum      = $this->forumService->getOrMake($dto->getForumExId(), $dto->getForumTitle());
        $topic      = $this->topicService->makeNotLoaded($dto->getExId(), $dto->getRawTitle(), $forum, $dto->getSize(), $dto->getExCreatedAt());

        /** @var \App\Application\Dto\RawImageDto $image */
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
            (int) $dto->getForumExId(),
            (string) $dto->getForumTitle(),
            (string) $dto->getRawTitle(),
            $this->sizeConverter->fromStringToInt($dto->getSize()),
            $this->dateTimeUtil->ymdHi($dto->getExCreatedAt()),
            $images
        );
    }
}
