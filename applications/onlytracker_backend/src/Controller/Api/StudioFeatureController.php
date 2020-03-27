<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class StudioFeatureController
{
    private StudioRepositoryInterface $studioRepository;

    public function __construct(StudioRepositoryInterface $studioRepository)
    {
        $this->studioRepository = $studioRepository;
    }

    public function ban(Request $request)
    {
        // TODO:

        $studioId = $request->attributes->get('studio');

        $studio = $this->studioRepository->find($studioId);
        if (null === $studio) {
            return new JsonResponse(null);
        }

        $studio->ban();

        $this->studioRepository->save($studio);

        return new JsonResponse(['ok']);
    }
}
