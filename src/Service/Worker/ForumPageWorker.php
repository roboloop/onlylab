<?php

namespace App\Service\Worker;

use App\Service\Assembler\EntireTopicAssembler;
use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

class ForumPageWorker
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

    public function work(string $content)
    {
        $content = $this->textCleaner->clearWhitespaces(
            $this->contentDecoder->decode($content)
        );

        $dtos = $this->forumHtmlParser->forumLinesDto($content);

        return $this->entireTopicAssembler->makeManyReviews($dtos);
    }
}
