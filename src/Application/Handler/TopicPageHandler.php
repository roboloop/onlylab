<?php

namespace OnlyTracker\Application\Handler;

use OnlyTracker\Application\CRUD\TopicCreationInterface;
use OnlyTracker\Application\Parser\TopicHtmlParser;

class TopicPageHandler
{
    private $topicCreator;
    private $htmlParser;

    public function __construct(TopicCreationInterface $topicCreator, TopicHtmlParser $htmlParser)
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
