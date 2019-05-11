<?php

namespace App\Service;

use App\Service\Handler\TopicPageHandler;
use App\Service\Request\RequestSender;
use App\Service\Request\TopicRequest;

class TopicGrabber
{
    private $requestSender;
    private $topicPageHandler;

    public function __construct(RequestSender $requestSender, TopicPageHandler $topicPageHandler)
    {
        $this->requestSender    = $requestSender;
        $this->topicPageHandler = $topicPageHandler;
    }

    public function grab($id)
    {
        $request    = new TopicRequest($id);
        $content    = $this->requestSender->send($request);
        $entity     = $this->topicPageHandler->handleAuth($content);

        return $entity;
    }
}
