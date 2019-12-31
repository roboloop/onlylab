<?php

namespace OnlyTracker\Domain\Factory;

use OnlyTracker\Domain\Entity\Forum;
use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Shared\DateTimeUtilInterface;

class ForumFactory
{
    private $dateTimeUtil;

    public function __construct(DateTimeUtilInterface $dateTimeUtil)
    {
        $this->dateTimeUtil = $dateTimeUtil;
    }

    public function make(ForumId $exId, string $title)
    {
        $createdAt = $this->dateTimeUtil->now();

        return new Forum($exId, $title, $createdAt);
    }
}
