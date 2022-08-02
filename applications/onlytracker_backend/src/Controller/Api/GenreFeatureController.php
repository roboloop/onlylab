<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Service\GenreService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class GenreFeatureController
{
    private GenreService $genreService;
    private NormalizerInterface $normalizer;

    public function __construct(GenreService $genreService, NormalizerInterface $normalizer)
    {
        $this->genreService = $genreService;
        $this->normalizer = $normalizer;
    }

    public function ban(Genre $genre)
    {
        $this->genreService->ban($genre);

        $normalized = $this->normalizer->normalize($genre);

        return new JsonResponse($normalized);
    }

    public function unban(Genre $genre)
    {
        $this->genreService->unban($genre);

        $normalized = $this->normalizer->normalize($genre);

        return new JsonResponse($normalized);
    }
}
