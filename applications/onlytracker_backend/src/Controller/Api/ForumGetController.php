<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Service\ForumService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ForumGetController
{
    private ForumService $forumService;
    private NormalizerInterface $normalizer;

    public function __construct(ForumService $forumService, NormalizerInterface $normalizer)
    {
        $this->forumService     = $forumService;
        $this->normalizer       = $normalizer;
    }

    public function __invoke()
    {
        $forums = $this->forumService->search();
        $normalized = $this->normalizer->normalize($forums);

        return new JsonResponse($normalized);
    }
}
