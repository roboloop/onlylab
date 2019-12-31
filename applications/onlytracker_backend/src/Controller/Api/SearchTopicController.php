<?php

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Search\TopicSearchCriteria;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class SearchTopicController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request)
    {
        // $res = $transformer->fromArray(['forumIds' => [14, 15], 'rawTitles' => ['asd', 111, 'zxc']], SearchDto::class);
        // $res = $transformer->fromArray(['forum_ids' => [14, 15], 'raw_titles' => ['asd', 'zxc']], SearchDto::class);

        $forumIds   = $request->query->get('forumIds');
        $rawTitles  = $request->query->get('rawTitles');

        $forumIds = $forumIds ? array_map(function (int $forumId) {
            return new ForumId($forumId);
        }, $forumIds) : null;

        TopicSearchCriteria::make()
            ->setForumIds($forumIds)
            ->setRawTitles($rawTitles);

        return '';

        return new JsonResponse([]);
    }
}
