<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Api;

use OnlyTracker\Application\Handler\ForumPageHandlerInterface;
use OnlyTracker\BackEnd\Dto\LoadDto;
use OnlyTracker\Infrastructure\Request\OnlyTracker\ForumPageRequest;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

final class TopicLoaderController
{
    private RequestSenderInterface $requestSender;
    private ForumPageHandlerInterface $forumPageHandler;

    public function __construct(RequestSenderInterface $requestSender, ForumPageHandlerInterface $forumPageHandler)
    {
        $this->requestSender = $requestSender;
        $this->forumPageHandler = $forumPageHandler;
    }
    
    public function __invoke(LoadDto $dto)
    {
        dd($this->forumPageHandler);
        
        if (empty($dto->forums())) {
            return new Response('No forums');
        }
        
        $response = new StreamedResponse(function () use ($dto) {
            [$start, $end] = $this->normalizeValues($dto);
            foreach ($dto->forums() as $forumId) {
                for ($i = $start; $i <= $end; $i++) {
                    $request = new ForumPageRequest((int) $forumId, $i);
                    try {
                        $content = $this->requestSender->send($request);
                        $this->forumPageHandler->handle($content);
                        echo "SUCCESS: Forum $forumId. Iter $i.\n";
                    } catch (ExceptionInterface $e) {
                        echo "FAIL: Forum $forumId. Iter $i. " . $e->getMessage() . "\n";
                    }
                    
                    try {
                        ob_flush();
                        flush();
                    } catch (\Throwable $throwable) {
                        
                    }
                    sleep(3);
                }
            }
        });

        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        
        return $response;

    }

    private function normalizeValues(LoadDto $dto): array
    {
        if (null === $dto->start() && null === $dto->end()) {
            throw new \InvalidArgumentException('Cannot normalize. Values "start" and "end" are dismissing.');
        }
        
        if ($dto->start() === null) {
            return [(int) $dto->end(), (int) $dto->end()];
        }
        
        if ($dto->end() === null) {
            return [(int) $dto->start(), (int) $dto->start()];
        }
        
        return [(int) $dto->start(), (int) $dto->end()];
    }
}
