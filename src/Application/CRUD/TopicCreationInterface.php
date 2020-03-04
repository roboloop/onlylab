<?php

namespace OnlyTracker\Application\CRUD;

use OnlyTracker\Application\Dto\RawTopicDto;
use OnlyTracker\Domain\Entity\Topic;

interface TopicCreationInterface
{
    public function createFromDto(RawTopicDto $dto): Topic;
}
