<?php

namespace App\Service\Handler;

use App\Contract\Service\HandlePageInterface;
use App\Dto\RawTopicDto;
use App\Service\GenreService;
use App\Service\TrackerIdCollector;
use App\Service\Maker\TopicMaker;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\StudioService;
use App\Service\TopicService;
use App\Service\Transformer\ArrayTransformer;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

/**
 * Forum page handler
 *
 * @package App\Service\Handler
 */
class ForumPageHandler implements HandlePageInterface
{
    private $contentDecoder;
    private $textCleaner;
    private $forumHtmlParser;
    private $genreService;
    private $arrayTransformer;
    private $studioService;
    private $topicMaker;
    private $topicService;
    private $trackerIdCollector;

    public function __construct(
        ContentDecoder $contentDecoder,
        TextCleaner $textCleaner,
        ForumHtmlParser $forumHtmlParser,
        GenreService $genreService,
        StudioService $studioService,
        TopicService $topicService,
        ArrayTransformer $arrayTransformer,
        TopicMaker $topicMaker,
        TrackerIdCollector $trackerIdCollector
    ) {
        $this->contentDecoder       = $contentDecoder;
        $this->textCleaner          = $textCleaner;
        $this->forumHtmlParser      = $forumHtmlParser;
        $this->genreService         = $genreService;
        $this->studioService        = $studioService;
        $this->arrayTransformer     = $arrayTransformer;
        $this->topicService         = $topicService;
        $this->topicMaker           = $topicMaker;
        $this->trackerIdCollector       = $trackerIdCollector;
    }

    /**
     * @inheritdoc
     */
    public function handleAuth(string $content)
    {
        // Prepare input string to processing.
        $content = $this->textCleaner->clearWhitespaces(
            $this->contentDecoder->decode($content)
        );

        // Convert html to array of entities containing raw data (id, whole title, size, etc.)
        $dtos = $this->forumHtmlParser->forumLinesDto($content);

        // Convert raw data to array of entities containing parsed data (title, year, quality, etc.)
        $topics = $this->makeManyTopics($dtos);

        return $topics;
    }

    /**
     * Create multiple topics
     *
     * @param array $dtos
     *
     * @return array
     */
    private function makeManyTopics(array $dtos)
    {
        $trackerIds         = $this->trackerIdCollector->collect($dtos);
        $topics         = $this->topicService->findByTrackerId($trackerIds);
        $genres         = $this->genreService->findAll();
        $studios        = $this->studioService->findAll();
        $genresKeyTitle = $this->arrayTransformer->setKeyFromSource($genres, 'title');
        $studiosKeyUrl  = $this->arrayTransformer->setKeyFromSource($studios, 'url');
        $topicsKeyTrackerId = $this->arrayTransformer->setKeyFromSource($topics, 'trackerId');

        $this->filterDtosFromExists($dtos, $topicsKeyTrackerId);

        return $this->makeManyReviews($dtos, $genresKeyTitle, $studiosKeyUrl);
    }

    /**
     * Create multiple topics featuring existing genres and studios
     *
     * @param array $dtos
     * @param array $allGenres
     * @param array $allStudios
     *
     * @return array
     */
    private function makeManyReviews(array $dtos, array $allGenres, array $allStudios)
    {
        foreach ($dtos as $dto) {
            $topics[] = $this->makeReview($dto, $allGenres, $allStudios);
        }

        return $topics ?? [];
    }

    /**
     * Create a topic with a list of existing genres and studios
     *
     * @param \App\Dto\RawTopicDto $dto
     * @param array                $allGenres
     * @param array                $allStudios
     *
     * @return \App\Entity\Topic
     */
    private function makeReview(RawTopicDto $dto, array $allGenres, array $allStudios)
    {
        return $this->topicMaker->makeTopic($dto, $allGenres, $allStudios);
    }

    private function filterDtosFromExists(array &$dtos, array $topicsKeyTrackerId)
    {
        foreach ($dtos as $key => $dto) {
            if (array_key_exists($dto->getTrackerId(), $topicsKeyTrackerId)) {
                unset($dtos[$key]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function handleNoAuth(string $content)
    {
        // Versions are identical
        return $this->handleAuth($content);
    }
}
