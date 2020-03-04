<?php

namespace OnlyTracker\Application\Handler;

use OnlyTracker\Application\CRUD\TopicCreationInterface;
use OnlyTracker\Application\Parser\ForumHtmlParser;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class ForumPageHandler
{
    private TopicCreationInterface $topicCreator;
    private ForumHtmlParser $htmlParser;
    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicCreationInterface $topicCreator, ForumHtmlParser $htmlParser, TopicRepositoryInterface $topicRepository)
    {
        $this->topicCreator     = $topicCreator;
        $this->htmlParser       = $htmlParser;
        $this->topicRepository  = $topicRepository;
    }

    public function handle(string $content)
    {
        $rawTopicDtos = $this->htmlParser->parseTopicsHeaders($content);

        array_walk($rawTopicDtos, fn($dto) => $this->topicCreator->createFromDto($dto));
    }
}
