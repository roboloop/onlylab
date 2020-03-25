<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Controller\Web;

use OnlyTracker\Infrastructure\Request\OnlyTracker\TopicPageRequest;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetProxyTopicController
{
    private RequestSenderInterface $requestSender;

    public function __construct(RequestSenderInterface $requestSender) {

        $this->requestSender = $requestSender;
    }

    public function __invoke(Request $request)
    {
        $id = $request->attributes->get('id');
        $request = new TopicPageRequest($id);
        $content = $this->requestSender->send($request);

        $headers = [
            'content-type' => 'text/html; charset=windows-1251',
        ];

        return new Response($content, 200, $headers);
    }
}
