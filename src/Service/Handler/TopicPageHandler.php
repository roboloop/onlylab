<?php

namespace App\Service\Handler;

use App\Contract\Service\HandlePageInterface;
use App\Contract\Service\Sanitizer\SanitizerInterface;
use App\Dto\RawTopicDto;
use App\Service\Assembler\EntireTopicAssembler;
use App\Service\Maker\TopicMaker;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\Parser\Html\TopicHtmlParser;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

class TopicPageHandler implements HandlePageInterface
{
    private $sanitizer;
    private $entireTopicAssembler;
    private $topicHtmlParser;
    private $topicMaker;

    public function __construct(
        SanitizerInterface $sanitizer,
        TopicHtmlParser $topicHtmlParser,
        EntireTopicAssembler $entireTopicAssembler,
        TopicMaker $topicMaker
    ) {
        $this->sanitizer            = $sanitizer;
        $this->topicHtmlParser      = $topicHtmlParser;
        $this->entireTopicAssembler = $entireTopicAssembler;
        $this->topicMaker           = $topicMaker;
    }

    /**
     * @inheritdoc
     */
    public function handleAuth(string $content)
    {
        // Prepare input string to processing.
        $content = $this->sanitizer->sanitize($content);

        // Convert html to entity containing raw data (id, title, size, images, etc.)
        $topicDto = $this->topicHtmlParser->rawTopicDto($content);

        // Convert raw data to entity containing parsed data (title, year, quality, etc.)
        $topic = $this->makeWholeTopic($topicDto);

        return $topic;
    }

    private function makeWholeTopic(RawTopicDto $dto)
    {
        return $this->topicMaker->makeTopic($dto);
    }

    /**
     * @inheritdoc
     */
    public function handleNoAuth(string $content)
    {
        return $this->handleAuth($content);
    }
}
