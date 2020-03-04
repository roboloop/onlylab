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
     * @param string      $id
     * @param string|null $title
     *
     * @return \OnlyTracker\Domain\Entity\Forum
     */
    public function getOrMake(string $id, string $title = null)
    {
        $forum = $this->forumRepository->find($id);

        if (null === $forum) {
            $title = $title ?: '(no forum name)';
            $forum = $this->forumFactory->make($id, $title);
            $this->forumRepository->save($forum);
        }

        return $forum;
    }

    /**
     * @return \OnlyTracker\Domain\Entity\Forum[]
     */
    public function search()
    {
        // TODO:

        return $this->forumRepository->findBy([], ['title' => 'ASC']);
    }
}
