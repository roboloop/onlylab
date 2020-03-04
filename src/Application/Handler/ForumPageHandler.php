<?php

namespace OnlyTracker\Application\Handler;

use OnlyTracker\Application\CRUD\TopicCreation;
use OnlyTracker\Application\Parser\ForumHtmlParser;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class ForumPageHandler implements ForumPageHandlerInterface
{
    private TopicCreation $topicCreator;
    private ForumHtmlParser $htmlParser;
    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicCreation $topicCreator, ForumHtmlParser $htmlParser, TopicRepositoryInterface $topicRepository)
    {
        $this->topicCreator     = $topicCreator;
        $this->htmlParser       = $htmlParser;
        $this->topicRepository  = $topicRepository;
    }

    public function handle(string $content)
    {
        $rawTopicDtos = $this->htmlParser->parseTopicsHeaders($content);

        // TODO: BATCH

        array_walk($rawTopicDtos, fn($dto) => $this->topicCreator->createFromDto($dto));
    }
}
