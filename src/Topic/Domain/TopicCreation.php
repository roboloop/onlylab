<?php

namespace OnlyTracker\Topic\Domain;

use DateTimeImmutable;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Identity\TopicId;

class TopicCreation
{
    public function create(TopicId $topicId, string $rawTitle, ?Size $size, ?DateTimeImmutable $exCreatedAt, ForumId $forumId, array $images)
    {

    }
}
