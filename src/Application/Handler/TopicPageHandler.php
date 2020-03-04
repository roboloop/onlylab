<?php

namespace OnlyTracker\Application\Handler;

use OnlyTracker\Application\CRUD\TopicCreation;
use OnlyTracker\Application\Parser\TopicHtmlParser;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class TopicPageHandler implements TopicPageHandlerInterface
{
    private TopicCreation $topicCreator;
    private TopicHtmlParser $htmlParser;
    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicCreation $topicCreator, TopicHtmlParser $htmlParser, TopicRepositoryInterface $topicRepository)
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
