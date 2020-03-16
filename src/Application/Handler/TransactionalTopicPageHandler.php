<?php

declare (strict_types = 1);

namespace OnlyTracker\Application\Handler;

use Doctrine\ORM\EntityManagerInterface;

class TransactionalTopicPageHandler implements TopicPageHandlerInterface
{
    private TopicPageHandlerInterface $pageHandler;
    private EntityManagerInterface $entityManager;

    public function __construct(TopicPageHandlerInterface $pageHandler, EntityManagerInterface $entityManager)
    {
        $this->pageHandler = $pageHandler;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(string $content)
    {
        try {
            return $this->entityManager->transactional(fn() => $this->pageHandler->handle($content));
        } finally {
            $this->entityManager->clear();
        }
    }
}
