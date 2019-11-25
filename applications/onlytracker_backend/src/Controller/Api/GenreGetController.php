<?php

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Service\GenreService;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GenreGetController
{
    private $genreService;

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
    }

    public function __invoke()
    {
        $genres = $this->genreService->search(null, null);

        foreach ($genres as $genre) {
            $res[$genre->getId()] = [
                $genre->getTitle(),
                $genre->getDescription(),
            ];
        }

        return new JsonResponse($res ?? []);
    }
}
