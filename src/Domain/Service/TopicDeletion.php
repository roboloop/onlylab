<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;

class TopicDeletion
{
    private $topicRepository;
    private $imageRepository;

    public function __construct(TopicRepositoryInterface $topicRepository, ImageRepositoryInterface $imageRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->imageRepository = $imageRepository;
    }

    public function delete(Topic $topic): void
    {
        $images = $topic->getImages();

        $topic->clearImages();
        $this->imageRepository->deleteMultiple($images);
        $this->topicRepository->delete($topic);
    }
}
