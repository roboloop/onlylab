<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Search\TopicSearchCriteria;
use OnlyTracker\Domain\Service\TopicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function __invoke(Request $request)
    {
        $rawGenres      = $request->query->get('genres');
        $rawStudios     = $request->query->get('studios');
        $rawQualities   = $request->query->get('qualities');
        $forums         = $request->query->get('forums');
        $criteria = TopicSearchCriteria::make();
        if (null !== $rawGenres) {
            $criteria->setGenreTitles(explode(',', $rawGenres));
        }
        if (null !== $rawStudios) {
            $criteria->setStudioUrls(explode(',', $rawStudios));
        }
        if (null !== $rawQualities) {
            $criteria->setQualities(explode(',', $rawQualities));
        }
        if (null !== $forums) {
            $criteria->setForumIds(explode(',', $forums));
        }

        $topics = $this->topicService->searchByCriteria($criteria);

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object, $format, $context) => $object->getId(),
            DateTimeNormalizer::FORMAT_KEY => 'd.m.Y H:i',
        ];

        $normalized = $this->normalizer->normalize($topics, null, $context);

        return new JsonResponse($normalized);
    }
}
