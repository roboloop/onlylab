<?php

namespace App\Service\Script;

use App\Behavior\TransactionManager;
use App\Service\Handler\TopicPageHandler;
use App\Service\Request\RequestSender;
use App\Service\Request\TopicRequest;

class CombTopic
{
    private $requestSender;
    private $topicPageHandler;
    private $transactionManager;

    public function __construct(RequestSender $requestSender, TopicPageHandler $topicPageHandler, TransactionManager $transactionManager)
    {
        $this->requestSender = $requestSender;
        $this->topicPageHandler = $topicPageHandler;
        $this->transactionManager = $transactionManager;
    }

    public function execute($id)
    {
        $request    = new TopicRequest($id);
        $content    = $this->requestSender->send($request);
        $entity     = $this->topicPageHandler->handleAuth($content);

        $this->transactionManager->transaction($entity);
    }
}
