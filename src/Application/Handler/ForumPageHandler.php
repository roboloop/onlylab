<?php

namespace OnlyTracker\Application\Handler;

use Doctrine\ORM\EntityManagerInterface;
use OnlyTracker\Application\CRUD\TopicCreation;
use OnlyTracker\Application\Parser\ForumHtmlParser;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class ForumPageHandler implements ForumPageHandlerInterface
{
    private TopicCreation $topicCreator;
    private ForumHtmlParser $htmlParser;
    private TopicRepositoryInterface $topicRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TopicCreation $topicCreator, ForumHtmlParser $htmlParser, TopicRepositoryInterface $topicRepository, EntityManagerInterface $entityManager)
    {
        $this->topicCreator     = $topicCreator;
        $this->htmlParser       = $htmlParser;
        $this->topicRepository  = $topicRepository;
        $this->entityManager    = $entityManager;
    }

    public function handle(string $content)
    {
        $rawTopicDtos = $this->htmlParser->parseTopicsHeaders($content);

        foreach ($rawTopicDtos as $rawTopicDto) {
            $this->entityManager->transactional(fn() => $this->topicCreator->createFromDto($rawTopicDto));
            $this->entityManager->clear();
        }
        
        // array_walk($rawTopicDtos, fn($dto) => $this->topicCreator->createFromDto($dto));
    }
}
