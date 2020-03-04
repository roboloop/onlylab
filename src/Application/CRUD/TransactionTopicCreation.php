<?php

declare (strict_types = 1);

namespace OnlyTracker\Application\CRUD;

use Doctrine\ORM\EntityManagerInterface;
use OnlyTracker\Application\Dto\RawTopicDto;
use OnlyTracker\Domain\Entity\Topic;

class TransactionTopicCreation implements TopicCreationInterface
{
    private $topicCreation;
    private $entityManager;

    public function __construct(TopicCreationInterface $topicCreation, EntityManagerInterface $entityManager)
    {
        $this->topicCreation = $topicCreation;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createFromDto(RawTopicDto $dto): Topic
    {
        return $this->entityManager->transactional(function () use ($dto) {
            return $this->topicCreation->createFromDto($dto);
        });
    }
}
