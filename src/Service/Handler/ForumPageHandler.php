<?php

namespace App\Service\Handler;

use App\Contract\Service\HandlePageInterface;
use App\Contract\Service\Sanitizer\SanitizerInterface;
use App\Dto\RawTopicDto;
use App\Service\GenreService;
use App\Service\Collector\TrackerIdCollector;
use App\Service\Maker\TopicMaker;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\StudioService;
use App\Service\TopicService;
use App\Service\Transformer\ArrayTransformer;

/**
 * Forum page handler
 *
 * @package App\Service\Handler
 */
class ForumPageHandler implements HandlePageInterface
{
    private $sanitizer;
    private $forumHtmlParser;
    private $arrayTransformer;
    private $topicMaker;
    private $topicService;
    private $trackerIdCollector;

    public function __construct(
        SanitizerInterface $sanitizer,
        ForumHtmlParser $forumHtmlParser,
        TopicService $topicService,
        ArrayTransformer $arrayTransformer,
        TopicMaker $topicMaker,
        TrackerIdCollector $trackerIdCollector
    ) {
        $this->sanitizer            = $sanitizer;
        $this->forumHtmlParser      = $forumHtmlParser;
        $this->arrayTransformer     = $arrayTransformer;
        $this->topicService         = $topicService;
        $this->topicMaker           = $topicMaker;
        $this->trackerIdCollector       = $trackerIdCollector;
    }

    /**
     * {@inheritdoc}
     */
    public function handleAuth(string $content)
    {
        // Prepare input string to processing.
        $content = $this->sanitizer->sanitize($content);

        // Convert html to array of entities containing raw data (id, whole title, size, etc.)
        $dtos = $this->forumHtmlParser->rawParticleTopicDto($content);

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
        $topicsKeyTrackerId = $this->arrayTransformer->setKeyFromSource($topics, 'trackerId');

        $this->filterDtosFromExists($dtos, $topicsKeyTrackerId);

        return $this->makeManyReviews($dtos);
    }

    /**
     * Create multiple topics featuring existing genres and studios
     *
     * @param array $dtos
     *
     * @return array
     */
    private function makeManyReviews(array $dtos)
    {
        foreach ($dtos as $dto) {
            $topics[] = $this->makeReview($dto);
        }

        return $topics ?? [];
    }

    /**
     * Create a topic with a list of existing genres and studios
     *
     * @param \App\Dto\RawTopicDto $dto
     *
     * @return \App\Entity\Topic
     */
    private function makeReview(RawTopicDto $dto)
    {
        return $this->topicMaker->makeTopic($dto);
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
     * {@inheritdoc}
     */
    public function handleNoAuth(string $content)
    {
        // Versions are identical
        return $this->handleAuth($content);
    }

    /**
     * This is the last page
     *
     * @param string $content
     *
     * @return bool
     */
    public function isLast(string $content)
    {
        list($current, $max) = $this->pageIs($content);

        return $current === $max;
    }

    /**
     * Returns the current page number and the maximum
     *
     * @param string $content
     *
     * @return array
     */
    public function pageIs(string $content)
    {
        $content = $this->sanitizer->sanitize($content);

        return $this->forumHtmlParser->pages($content);
    }
}
