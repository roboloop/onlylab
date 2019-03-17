<?php

namespace App\Service\Handler;

use App\Dto\ForumLineDto;
use App\Service\Assembler\EntireTopicAssembler;
use App\Service\GenreService;
use App\Service\Maker\TopicMaker;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\StudioService;
use App\Service\Transformer\ArrayTransformer;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

/**
 * Forum page handler
 *
 * @package App\Service\Handler
 */
class ForumPageHandler
{
    private $contentDecoder;
    private $textCleaner;
    private $forumHtmlParser;
    private $genreService;
    private $arrayTransformer;
    private $studioService;
    private $topicMaker;

    public function __construct(
        ContentDecoder $contentDecoder,
        TextCleaner $textCleaner,
        ForumHtmlParser $forumHtmlParser,
        GenreService $genreService,
        StudioService $studioService,
        ArrayTransformer $arrayTransformer,
        TopicMaker $topicMaker
    ) {
        $this->contentDecoder       = $contentDecoder;
        $this->textCleaner          = $textCleaner;
        $this->forumHtmlParser      = $forumHtmlParser;
        $this->genreService         = $genreService;
        $this->studioService        = $studioService;
        $this->arrayTransformer     = $arrayTransformer;
        $this->topicMaker           = $topicMaker;
    }

    /**
     * Processing a forum page of the version received with authentication
     *
     * @param string $content
     *
     * @return array
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
        $genres         = $this->genreService->findAll();
        $studios        = $this->studioService->findAll();
        $genresKeyTitle = $this->arrayTransformer->setKeyFromSource($genres, 'title', true);
        $studiosKeyUrl  = $this->arrayTransformer->setKeyFromSource($studios, 'url', true);

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
     * @param \App\Dto\ForumLineDto $dto
     * @param array                 $allGenres
     * @param array                 $allStudios
     *
     * @return \App\Entity\Topic
     */
    private function makeReview(ForumLineDto $dto, array $allGenres, array $allStudios)
    {
        return $this->topicMaker->makeTopic($dto, $allGenres, $allStudios);
    }

    /**
     * Processing a forum page of the version received without authentication
     *
     * @param string $content
     *
     * @return array
     */
    public function handleNoAuth(string $content)
    {
        // Versions are identical
        return $this->handleAuth($content);
    }
}
