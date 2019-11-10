<?php

namespace App\Application\Creator;

use App\Domain\Service\ForumService;
use App\Domain\Service\GenreService;
use App\Domain\Service\StudioService;
use App\Domain\Service\TopicService;
use App\Dto\RawTopicDto;
use App\Infrastructure\Util\Parser\Title\TitleParserManager;

class TopicCreator
{
    private $parserManager;
    private $genreService;
    private $studioService;
    private $forumService;
    private $topicService;

    public function __construct(
        TitleParserManager $parserManager,
        GenreService $genreService,
        StudioService $studioService,
        ForumService $forumService,
        TopicService $topicService
    ) {
        $this->parserManager    = $parserManager;
        $this->genreService     = $genreService;
        $this->studioService    = $studioService;
        $this->forumService     = $forumService;
        $this->topicService     = $topicService;
    }

    public function createFromDto(RawTopicDto $dto)
    {
        $rawGenres  = $this->parserManager->genres($dto->getRawTitle());
        $genres     = $this->genreService->getOrMakeOrBoth($rawGenres);

        $rawStudios = $this->parserManager->studios($dto->getRawTitle());
        $studios    = $this->studioService->getOrMakeOrBoth($rawStudios);

        $forum      = $this->forumService->getOrMake($dto->getForumId(), $dto->getForumTitle());

        // TODO:
        $images     = [];

        $topic      = $this->topicService->makeNotLoaded($dto->getTrackerId(), $dto->getRawTitle(), $forum, $dto->getSize(), $dto->getTrackerCreatedAt());

        // TODO:
    }
}
