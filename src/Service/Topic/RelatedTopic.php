<?php

namespace App\Service\Topic;

use App\Entity\Topic;
use App\Repository\TopicRepository;

class RelatedTopic
{
    private $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function related(Topic $topic)
    {
        $title = trim($topic->getTitle());
        $exceptId = $topic->getId();

        return $this->topicRepository->related($title, [$exceptId]);
    }
}
