<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\TopicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class TopicFeatureController
{
    private TopicService $topicService;
    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicService $topicService, TopicRepositoryInterface $topicRepository)
    {
        $this->topicService = $topicService;
        $this->topicRepository = $topicRepository;
    }

    public function reload(Request $request)
    {
        // TODO:
        $topicId = $request->attributes->get('topic');
        $topic = $this->topicRepository->find($topicId);
        // $this->topicService->reload($topic);

        return new JsonResponse();
    }
}
