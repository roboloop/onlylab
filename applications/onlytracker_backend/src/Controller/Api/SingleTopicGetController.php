<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Application\Handler\TopicPageHandlerInterface;
use OnlyTracker\Domain\Repository\TopicRepositoryInterface;
use OnlyTracker\Domain\Service\TopicService;
use OnlyTracker\Infrastructure\Request\OnlyTracker\TopicPageRequest;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class SingleTopicGetController
{
    private TopicRepositoryInterface $topicRepository;
    private NormalizerInterface $normalizer;
    private RequestSenderInterface $requestSender;
    private TopicPageHandlerInterface $topicPageHandler;

    public function __construct(TopicRepositoryInterface $topicRepository, NormalizerInterface $normalizer, RequestSenderInterface $requestSender, TopicPageHandlerInterface $topicPageHandler)
    {
        $this->topicRepository = $topicRepository;
        $this->normalizer = $normalizer;
        $this->requestSender = $requestSender;
        $this->topicPageHandler = $topicPageHandler;
    }

    public function __invoke(Request $request)
    {
        $topic = $this->topicRepository->find($request->attributes->get('topic'));

        if (null === $topic) {
            return new JsonResponse(null, 404);
        }

        if (!$topic->isLoaded()) {
            $request = new TopicPageRequest($topic->getId());
            try {
                $content = $this->requestSender->send($request);
                $this->topicPageHandler->handle($content);
                $topic = $this->topicRepository->find($topic->getId());
            } catch (ExceptionInterface $e) {
                return new JsonResponse(null, 500);
            }
        }

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn($object, $format, $context) => $object->getId(),
            DateTimeNormalizer::FORMAT_KEY => 'd.m.Y H:i',
        ];

        $normalized = $this->normalizer->normalize($topic, null, $context);

        $normalized['images'] = [
            [ 'original' => '/storage/1.jpeg',],
            [ 'original' => '/storage/2.jpeg',],
            [ 'original' => '/storage/3.jpeg',],
        ];

        return new JsonResponse($normalized);
    }
}
