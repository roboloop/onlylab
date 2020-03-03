<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Embeddable\ParsedTitle;
use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use OnlyTracker\Domain\Entity\Topic;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;
use DateTimeImmutable;

class TopicFactory
{
    private $dateTimeUtil;

    public function __construct(DateTimeUtilInterface $dateTimeUtil)
    {
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(string $id, ParsedTitle $parsedTitle, Forum $forum, ?Size $size, ?DateTimeImmutable $exCreatedAt, bool $isLoaded)
    {
        $createdAt = $this->dateTimeUtil->now();

        return new Topic($id, $parsedTitle, $forum, $size, $exCreatedAt, $createdAt, $isLoaded);
    }
}
