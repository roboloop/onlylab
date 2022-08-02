<?php

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Service\GenreService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class GenreGetController
{
    private GenreService $genreService;
    private NormalizerInterface $normalizer;

    public function __construct(GenreService $genreService, NormalizerInterface $normalizer)
    {
        $this->genreService = $genreService;
        $this->normalizer = $normalizer;
    }

    public function __invoke()
    {
        $genres = $this->genreService->search(null, null);
        $genres = $this->genreService->groupByFirstLetter($genres);
        $normalized = $this->normalizer->normalize($genres);

        return new JsonResponse($normalized);
    }
}
