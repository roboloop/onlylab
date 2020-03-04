<?php

declare (strict_types = 1);

namespace OnlyTracker\Application\Handler;

use Doctrine\ORM\EntityManagerInterface;

class TransactionalForumPageHandler implements ForumPageHandlerInterface
{
    private ForumPageHandlerInterface $pageHandler;
    private EntityManagerInterface $entityManager;

    public function __construct(ForumPageHandlerInterface $pageHandler, EntityManagerInterface $entityManager)
    {
        $this->pageHandler = $pageHandler;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(string $content)
    {
        return $this->entityManager->transactional(fn() => $this->pageHandler->handle($content));
    }
}
