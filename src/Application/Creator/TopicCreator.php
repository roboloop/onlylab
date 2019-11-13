<?php

namespace App\Application\Creator;

use App\Application\Dto\RawTopicDto;
use App\Application\Exception\InvalidArgumentWhenCreatingTopicException;
use App\Domain\Service\ForumService;
use App\Domain\Service\GenreService;
use App\Domain\Service\StudioService;
use App\Domain\Service\TopicService;
use App\Domain\Shared\DateTimeUtilInterface;
use App\Infrastructure\Util\Parser\Title\TitleParserManager;
use App\Infrastructure\Util\SizeConverter;
use Assert\AssertionFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TopicCreator
{
    private $validator;
    private $parserManager;
    private $genreService;
    private $studioService;
    private $forumService;
    private $topicService;
    private $dateTimeUtil;
    private $sizeConverter;

    public function __construct(
        ValidatorInterface $validator,
        TitleParserManager $parserManager,
        GenreService $genreService,
        StudioService $studioService,
        ForumService $forumService,
        TopicService $topicService,
        DateTimeUtilInterface $dateTimeUtil,
        SizeConverter $sizeConverter
    ) {
        $this->validator        = $validator;
        $this->parserManager    = $parserManager;
        $this->genreService     = $genreService;
        $this->studioService    = $studioService;
        $this->forumService     = $forumService;
        $this->topicService     = $topicService;
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

        // TODO:
        $images     = [];
        $images = $this->image

        $topic      = $this->topicService->makeNotLoaded($dto->getExId(), $dto->getRawTitle(), $forum, $dto->getSize(), $dto->getExCreatedAt());

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
        try {
            $errors = $this->validator->validate($dto);

            if ($errors->count()) {
                throw new InvalidArgumentWhenCreatingTopicException;
            }
            // Assertion::notEmpty($dto->getExId(), 'Ex id must be specified');
            // Assertion::notEmpty($dto->getRawTitle(), 'Raw title must be specified');
            // Assertion::notEmpty($dto->getForumTitle(), 'Forum title must be specified');
            // Assertion::notEmpty($dto->getForumExId(), 'Forum ex id must be specified');
            // Assertion::notEmpty($dto->getSize(), 'Size must be specified');
            // Assertion::notEmpty($dto->getExCreatedAt(), 'Ex created at timestamp must be specified');
        } catch (AssertionFailedException $e) {
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
