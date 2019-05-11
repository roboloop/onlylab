<?php

namespace App\Service;

use App\Contract\Service\TopicServiceInterface;
use App\Repository\TopicRepository;

class TopicService implements TopicServiceInterface
{
    private $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function findAll()
    {
        return $this->topicRepository->findAll();
    }

    public function removeCompletely($id)
    {
        $id = is_array($id) ? $id : array($id);

        return $this->topicRepository->removeCompletely($id);
    }

    public function findByTrackerId(array $ids)
    {
        return $this->topicRepository->findBy(['trackerId' => $ids]);
    }

    public function findExistsTrackerIds(array $ids)
    {
        $existsIds = $this->topicRepository->findExistsTrackerIds($ids);

        return array_values(array_column($existsIds, 'id'));
    }
}
