<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Repository\ForumRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SearchDataController
{
    private ForumRepositoryInterface $forumRepository;
    private NormalizerInterface $normalizer;

    public function __construct(ForumRepositoryInterface $forumRepository, NormalizerInterface $normalizer)
    {
        $this->forumRepository = $forumRepository;
        $this->normalizer = $normalizer;
    }

    public function __invoke()
    {
        $forums = $this->forumRepository->findBy([]);
        $forumsNormalized = $this->normalizer->normalize($forums);

        return new JsonResponse([
            'forums' => $forumsNormalized,
        ]);
    }
}
