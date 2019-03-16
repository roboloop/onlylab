<?php

namespace App\Service\Handler;

use App\Service\Assembler\EntireTopicAssembler;
use App\Service\Parser\Html\ForumHtmlParser;
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
    private $entireTopicAssembler;

    public function __construct(
        ContentDecoder $contentDecoder,
        TextCleaner $textCleaner,
        ForumHtmlParser $forumHtmlParser,
        EntireTopicAssembler $entireTopicAssembler
    ) {
        $this->contentDecoder       = $contentDecoder;
        $this->textCleaner          = $textCleaner;
        $this->forumHtmlParser      = $forumHtmlParser;
        $this->entireTopicAssembler = $entireTopicAssembler;
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
        return $this->entireTopicAssembler->makeManyReviews($dtos);
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
