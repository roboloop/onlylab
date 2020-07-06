<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\BackEnd\Dto\SearchDto;
use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Search\TopicSearchCriteria;
use OnlyTracker\Domain\Service\TopicService;
use OnlyTracker\Shared\Application\Search\QuerySearchHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SearchTopicController
{
    private QuerySearchHandler $querySearchHandler;
    private TopicRepositoryInterface $topicRepository;
    private NormalizerInterface $normalizer;
    private TopicService $topicService;

    public function __construct(
        QuerySearchHandler $querySearchHandler,
        TopicRepositoryInterface $topicRepository,
        NormalizerInterface $normalizer,
        TopicService $topicService
    ) {
        $this->querySearchHandler   = $querySearchHandler;
        $this->topicRepository      = $topicRepository;
        $this->normalizer           = $normalizer;
        $this->topicService         = $topicService;
    }

    public function __invoke(SearchDto $dto)
    {
        $criteria = $this->makeCriteriaFromDto($dto);
        $topics = $this->topicService->searchByCriteria($criteria);

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object, $format, $context) => $object->getId(),
            DateTimeNormalizer::FORMAT_KEY => 'd.m.Y H:i',
        ];

        $normalized = $this->normalizer->normalize($topics, null, $context);

        return new JsonResponse($normalized);
    }

    private function makeCriteriaFromDto(SearchDto $dto): TopicSearchCriteria
    {
        // $forumIds       = is_array($forumIds = $dto->forums()) && in_array('-1', $forumIds, true) ? [] : $forumIds;
        $forumIds       = $dto->forums();
        $genreTitles    = $this->querySearchHandler->explodeIntoWords($dto->genres());
        $studioUrls     = $this->querySearchHandler->explodeIntoWords($dto->studios());
        $years          = $this->querySearchHandler->explodeIntoWords($dto->years());
        $qualities      = $this->querySearchHandler->explodeIntoWords($dto->qualities());
        $titles         = $this->querySearchHandler->explodeIntoWords($dto->title());
        $rawTitles      = $this->querySearchHandler->explodeIntoWords($dto->rawTitle());

        $studioStatuses = is_array($dto->studioStatuses())
            ? array_map(fn ($value) => new StudioStatus($value), $dto->studioStatuses())
            : null;

        $criteria = TopicSearchCriteria::make()
            ->setForumIds($forumIds)
            ->setGenreTitles($genreTitles)
            ->setStudioUrls($studioUrls)
            ->setYears($years)
            ->setQualities($qualities)
            ->setTitles($titles)
            ->setRawTitles($rawTitles)
            ->setStudioStatuses($studioStatuses)
        ;

        return $criteria;
    }
}
