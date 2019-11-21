<?php

namespace OnlyTracker\Domain\Service;

use OnlyTracker\Domain\Factory\ForumFactory;
use OnlyTracker\Domain\Repository\ForumRepositoryInterface;

class ForumService
{
    private $forumRepository;
    private $forumFactory;

    public function __construct(ForumRepositoryInterface $forumRepository, ForumFactory $forumFactory)
    {
        $this->forumRepository  = $forumRepository;
        $this->forumFactory     = $forumFactory;
    }

    /**
     * @param int         $forumExId
     * @param string|null $title
     *
     * @return \App\Domain\Entity\Forum
     */
    public function getOrMake(int $forumExId, string $title = null)
    {
        $forums = $this->forumRepository->findBy(['exId' => $forumExId], ['createdAt' => 'DESC']);

        $forum = reset($forums);

        if (false === $forum) {
            $title = $title ?: '(no forum name)';
            $forum = $this->forumFactory->make($forumExId, $title);
            $this->forumRepository->save($forum);
        }

        return $forum;
    }
}
