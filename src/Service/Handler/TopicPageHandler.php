<?php

namespace App\Service\Handler;

use App\Service\Assembler\EntireTopicAssembler;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\Parser\Html\TopicHtmlParser;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

class TopicPageHandler
{
    private $contentDecoder;
    private $textCleaner;
    private $entireTopicAssembler;
    private $topicHtmlParser;

    public function __construct(
        ContentDecoder $contentDecoder,
        TextCleaner $textCleaner,
        TopicHtmlParser $topicHtmlParser,
        EntireTopicAssembler $entireTopicAssembler
    ) {
        $this->contentDecoder       = $contentDecoder;
        $this->textCleaner          = $textCleaner;
        $this->topicHtmlParser      = $topicHtmlParser;
        $this->entireTopicAssembler = $entireTopicAssembler;
    }

    public function work(string $content)
    {
        $content = $this->textCleaner->clearWhitespaces(
            $this->contentDecoder->decode($content)
        );

        $dto = $this->topicHtmlParser->pics($content);

        return $this->entireTopicAssembler->makeEntire($dto);
    }
}
