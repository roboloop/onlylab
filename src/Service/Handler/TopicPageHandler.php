<?php

namespace App\Service\Handler;

use App\Contract\Service\HandlePageInterface;
use App\Dto\RawTopicDto;
use App\Service\Assembler\EntireTopicAssembler;
use App\Service\Maker\TopicMaker;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\Parser\Html\TopicHtmlParser;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

class TopicPageHandler implements HandlePageInterface
{
    private $contentDecoder;
    private $textCleaner;
    private $entireTopicAssembler;
    private $topicHtmlParser;
    private $topicMaker;

    public function __construct(
        ContentDecoder $contentDecoder,
        TextCleaner $textCleaner,
        TopicHtmlParser $topicHtmlParser,
        EntireTopicAssembler $entireTopicAssembler,
        TopicMaker $topicMaker
    ) {
        $this->contentDecoder       = $contentDecoder;
        $this->textCleaner          = $textCleaner;
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
        $content = $this->textCleaner->clearWhitespaces(
            $this->contentDecoder->decode($content)
        );

        // Convert html to entity containing raw data (id, title, size, images, etc.)
        $dto = $this->topicHtmlParser->rawImagesDto($content);

        // Convert raw data to entity containing parsed data (title, year, quality, etc.)
        $topic = $this->makeTopic($dto);

        return $topic;
    }

    private function makeReview(RawTopicDto $dto, array $allGenres, array $allStudios)
    {
        return $this->topicMaker->makeTopic($dto, $allGenres, $allStudios);
    }

    /**
     * @inheritdoc
     */
    public function handleNoAuth(string $content)
    {
        return $this->handleAuth($content);
    }

    private function makeManyImages(array $dtos)
    {

    }
}
