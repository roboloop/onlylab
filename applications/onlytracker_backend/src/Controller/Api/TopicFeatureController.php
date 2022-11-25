<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Application\Handler\TopicPageHandlerInterface;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\TopicService;
use OnlyTracker\Infrastructure\Request\OnlyTracker\TopicPageRequest;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

final class TopicFeatureController
{
    private TopicService $topicService;
    private TopicRepositoryInterface $topicRepository;
    private RequestSenderInterface $requestSender;
    private TopicPageHandlerInterface $topicPageHandler;

    public function __construct(TopicService $topicService, TopicRepositoryInterface $topicRepository, RequestSenderInterface $requestSender, TopicPageHandlerInterface $topicPageHandler)
    {
        $this->topicService     = $topicService;
        $this->topicRepository  = $topicRepository;
        $this->requestSender    = $requestSender;
        $this->topicPageHandler = $topicPageHandler;
    }

    public function reload(Request $request)
    {
        // TODO:
        $topicId = $request->attributes->get('topic');
        $topic = $this->topicRepository->find($topicId);
        // $this->topicService->reload($topic);

        $request = new TopicPageRequest($topic->getId());
        try {
            $content = $this->requestSender->send($request);
            $this->topicPageHandler->handle($content);
            $topic = $this->topicService->getFullTopicById($topic->getId());
        } catch (ExceptionInterface $e) {
            return new JsonResponse((string) $e, 500);
        }

        return new RedirectResponse('/topics/' . $topic->getId());
    }
}
