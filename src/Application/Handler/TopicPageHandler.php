<?php

namespace OnlyTracker\Application\Handler;

use OnlyTracker\Application\CRUD\TopicCreationInterface;
use OnlyTracker\Application\Parser\TopicHtmlParser;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class TopicPageHandler
{
    private TopicCreationInterface $topicCreator;
    private TopicHtmlParser $htmlParser;
    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicCreationInterface $topicCreator, TopicHtmlParser $htmlParser, TopicRepositoryInterface $topicRepository)
    {
        $this->topicCreator     = $topicCreator;
        $this->htmlParser       = $htmlParser;
        $this->topicRepository  = $topicRepository;
    }

    public function handle(string $content)
    {
        $rawTopicDto = $this->htmlParser->parseTopic($content);

        $topic = $this->topicCreator->createFromDto($rawTopicDto);

        $topic->makeAsLoaded();

        $this->topicRepository->save($topic);
    }
}
