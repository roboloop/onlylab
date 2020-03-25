<?php

declare (strict_types = 1);

namespace OnlyTracker\Application\CRUD;

use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class TopicDeletionRemoved
{
    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function delete(string $topicId)
    {
        if ($topic = $this->topicRepository->find($topicId)) {
            // TODO: copy old values, replace empty attributes on new entity by old values
            $topic->getImages();
            $this->topicRepository->delete($topic);
        }
    }
}
