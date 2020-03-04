<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Service\TopicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TopicGetController
{
    private TopicService $topicService;
    private NormalizerInterface $normalizer;

    public function __construct(TopicService $topicService, NormalizerInterface $normalizer)
    {
        $this->topicService = $topicService;
        $this->normalizer = $normalizer;
    }

    public function __invoke()
    {
        $topics = $this->topicService->search(null, null, null);
        $normalized = $this->normalizer->normalize($topics, null, ['groups' => 'sample']);

        return new JsonResponse($normalized);
    }
}
