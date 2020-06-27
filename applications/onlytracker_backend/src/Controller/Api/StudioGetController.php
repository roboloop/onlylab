<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Service\StudioService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class StudioGetController
{
    private StudioService $studioService;
    private NormalizerInterface $normalizer;

    public function __construct(StudioService $studioService, NormalizerInterface $normalizer)
    {
        $this->studioService    = $studioService;
        $this->normalizer       = $normalizer;
    }

    public function __invoke()
    {
        $studios = $this->studioService->search(null, null);
        $studios = $this->studioService->groupByFirstLetter($studios);
        $normalized = $this->normalizer->normalize($studios);

        return new JsonResponse($normalized);
    }
}
