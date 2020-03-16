<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Service\TopicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
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

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object, $format, $context) => $object->getId(),
            DateTimeNormalizer::FORMAT_KEY => 'd.m.Y H:i',
        ];

        $normalized = $this->normalizer->normalize($topics, null, $context);

        return new JsonResponse($normalized);
    }
}
