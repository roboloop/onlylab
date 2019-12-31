<?php

namespace OnlyTracker\Application\Handler;

use OnlyTracker\Application\CRUD\TopicCreation;
use OnlyTracker\Application\Parser\TopicHtmlParser;

class TopicPageHandler
{
    private $topicCreator;
    private $htmlParser;

    public function __construct(TopicCreation $topicCreator, TopicHtmlParser $htmlParser)
    {
        $this->topicCreator = $topicCreator;
        $this->htmlParser = $htmlParser;
    }

    public function handle(string $content)
    {
        $rawTopicDto = $this->htmlParser->parseTopic($content);

        $this->topicCreator->createFromDto($rawTopicDto);
    }
}
