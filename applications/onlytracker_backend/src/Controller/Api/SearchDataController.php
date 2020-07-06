<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Repository\ForumRepositoryInterface;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\TopicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SearchDataController
{
    private ForumRepositoryInterface $forumRepository;
    private TopicService $topicService;
    private NormalizerInterface $normalizer;

    public function __construct(ForumRepositoryInterface $forumRepository, TopicService $topicService, NormalizerInterface $normalizer)
    {
        $this->forumRepository = $forumRepository;
        $this->topicService = $topicService;
        $this->normalizer = $normalizer;
    }

    public function __invoke()
    {
        $forums = $this->forumRepository->findBy([]);
        $forumsNormalized = $this->normalizer->normalize($forums);
        $totalTopics = $this->topicService->totalTopics();
        $totalLoadedTopics = $this->topicService->totalLoadedTopics();

        return new JsonResponse([
            'forums' => $forumsNormalized,
            'studioStatuses' => StudioStatus::all(),
            'totalTopics' => $totalTopics,
            'totalLoadedTopics' => $totalLoadedTopics,
        ]);
    }
}
