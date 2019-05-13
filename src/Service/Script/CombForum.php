<?php

namespace App\Service\Script;

use App\Behavior\TransactionManager;
use App\Service\Collector\ValueCollector;
use App\Service\Handler\ForumPageHandler;
use App\Service\Manager\SleepManager;
use App\Service\Request\ForumRequest;
use App\Service\Request\RequestSender;
use App\Service\TopicService;

class CombForum
{
    private $requestSender;
    private $forumPageHandler;
    private $topicService;
    private $valueCollector;
    private $transactionManager;
    private $sleepManager;

    private $skipExists = true;
    private $withPause = true;

    public function __construct(
        RequestSender $requestSender,
        ForumPageHandler $forumPageHandler,
        TopicService $topicService,
        ValueCollector $valueCollector,
        TransactionManager $transactionManager,
        SleepManager $sleepManager
    ) {
        $this->requestSender        = $requestSender;
        $this->forumPageHandler     = $forumPageHandler;
        $this->topicService         = $topicService;
        $this->valueCollector       = $valueCollector;
        $this->transactionManager   = $transactionManager;
        $this->sleepManager         = $sleepManager;
    }

    public function isSkipExists()
    {
        return $this->skipExists;
    }

    public function setSkipExists(bool $skipExists)
    {
        $this->skipExists = $skipExists;

        return $this;
    }

    public function execute($id)
    {
        $page = 1;

        do {
            $request = new ForumRequest($id, $page);
            $content = $this->requestSender->send($request);

            $entities = $this->forumPageHandler->handleAuth($content);

            // If all entities on the page are already present in the database, then drop
            if (empty($entities)) {
                break;
            }

            $this->transactionManager->transaction($entities);

            if ($this->withPause) {
                $this->sleepManager->perRequest();
            }

            $page++;

        } while ( !$this->forumPageHandler->isLast($content) );

        // TODO: decided at a lower level
        // if ($this->isSkipExists()) {
        //     $entities = $this->excludeExists($entities);
        // }
    }

    private function excludeExists($entities)
    {
        $ids = $this->valueCollector->collect($entities, 'trackerId');
        $exists = $this->topicService->findExistsTrackerIds($ids);

        $diff = array_diff($ids, $exists);

        return $this->filter($entities, $diff);
    }

    private function filter($entities, $diff)
    {
        return array_filter($entities, function ($value) use ($diff) {
            return ! array_search($value->getTrackerId(), $diff);
        });
    }
}
