<?php

namespace App\Service\Worker;

use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

class ForumPageWorker
{
    private $contentDecoder;
    private $textCleaner;
    private $forumHtmlParser;

    public function __construct(
        ContentDecoder $contentDecoder,
        TextCleaner $textCleaner,
        ForumHtmlParser $forumHtmlParser
    ) {
        $this->contentDecoder   = $contentDecoder;
        $this->textCleaner      = $textCleaner;
        $this->forumHtmlParser  = $forumHtmlParser;
    }

    public function work(string $content)
    {
        $content = $this->textCleaner->clearWhitespaces(
            $this->contentDecoder->decode($content)
        );

        $lines = $this->forumHtmlParser->forumLines($content);

        return true;
    }
}
