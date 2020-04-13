<?php

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\BackEnd\Dto\SearchDto;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Search\TopicSearchCriteria;
use OnlyTracker\Shared\Application\Search\QuerySearchHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SearchTopicController
{
    private $querySearchHandler;
    private $topicRepository;
    private $normalizer;

    public function __construct(QuerySearchHandler $querySearchHandler, TopicRepositoryInterface $topicRepository, NormalizerInterface $normalizer)
    {
        $this->querySearchHandler   = $querySearchHandler;
        $this->topicRepository      = $topicRepository;
        $this->normalizer           = $normalizer;
    }

    public function __invoke(SearchDto $dto)
    {
        // TODO:

        $genreTitles    = $this->querySearchHandler->explodeIntoWords($dto->genreTitles());
        $studioUrls     = $this->querySearchHandler->explodeIntoWords($dto->studioUrls());
        $qualities      = $this->querySearchHandler->explodeIntoWords($dto->qualities());
        $title          = $dto->title();

        $criteria = TopicSearchCriteria::make()
            ->setTitles($title ? [$title] : [])
            ->setGenreTitles($genreTitles)
            ->setStudioUrls($studioUrls)
            ->setQualities($qualities)
        ;

        $topics = $this->topicRepository->search($criteria);

        $normalized = $this->normalizer->normalize($topics);

        return new JsonResponse($normalized);
    }
}
