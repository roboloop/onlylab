<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Repository\ForumRepositoryInterface;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SearchDataController
{
    private ForumRepositoryInterface $forumRepository;
    private NormalizerInterface $normalizer;
    private TopicRepositoryInterface $topicRepository;

    public function __construct(ForumRepositoryInterface $forumRepository, TopicRepositoryInterface $topicRepository, NormalizerInterface $normalizer)
    {
        $this->forumRepository = $forumRepository;
        $this->topicRepository = $topicRepository;
        $this->normalizer = $normalizer;
    }

    public function __invoke()
    {
        $forums = $this->forumRepository->findBy([]);
        $forumsNormalized = $this->normalizer->normalize($forums);
        $totalTopics = $this->topicRepository->totalTopics();

        return new JsonResponse([
            'forums' => $forumsNormalized,
            'studioStatuses' => StudioStatus::all(),
            'totalTopics' => $totalTopics,
        ]);
    }
}
