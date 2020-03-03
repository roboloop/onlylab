<?php

namespace OnlyTracker\Topic\Domain;

use DateTimeImmutable;
use OnlyTracker\Domain\Entity\ObjectValue\Size;

class TopicCreation
{
    public function create(string $topicId, string $rawTitle, ?Size $size, ?DateTimeImmutable $exCreatedAt, string $forumId, array $images)
    {

    }
}
