<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Domain\Service\StudioService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class StudioFeatureController
{
    private StudioRepositoryInterface $studioRepository;
    private StudioService $studioService;
    private NormalizerInterface $normalizer;

    public function __construct(StudioRepositoryInterface $studioRepository, StudioService $studioService, NormalizerInterface $normalizer)
    {
        $this->studioRepository = $studioRepository;
        $this->studioService = $studioService;
        $this->normalizer = $normalizer;
    }

    public function ban(Studio $studio)
    {
        $this->studioService->ban($studio);

        $normalized = $this->normalizer->normalize($studio);

        return new JsonResponse($normalized);
    }

    public function prefer(Studio $studio)
    {
        $this->studioService->prefer($studio);

        $normalized = $this->normalizer->normalize($studio);

        return new JsonResponse($normalized);
    }

    public function typical(Studio $studio)
    {
        $this->studioService->typical($studio);

        $normalized = $this->normalizer->normalize($studio);

        return new JsonResponse($normalized);
    }
}
