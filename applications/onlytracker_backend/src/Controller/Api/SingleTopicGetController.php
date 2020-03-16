<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\TopicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SingleTopicGetController
{
    private TopicRepositoryInterface $topicRepository;
    private NormalizerInterface $normalizer;

    public function __construct(TopicRepositoryInterface $topicRepository, NormalizerInterface $normalizer)
    {
        $this->topicRepository = $topicRepository;
        $this->normalizer = $normalizer;
    }

    public function __invoke(Request $request)
    {
        $topic = $this->topicRepository->findBy($request->attributes->get('topic'), [
            'parsedTitle.title' => 'ASC',
            'parsedTitle.rawTitle' => 'ASC',
            'genres.title' => 'ASC',
        ]);

        $topic = $this->topicRepository->findBy($request->attributes->get('topic'));

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];

        $normalized = $this->normalizer->normalize($topic, null, $context);

        return new JsonResponse($normalized);
    }
}
