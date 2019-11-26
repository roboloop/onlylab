<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity;

use OnlyTracker\Domain\Identity\ForumId;
use DateTimeImmutable;

class Forum
{
    private $id;
    private $title;
    private $createdAt;

    public function __construct(ForumId $id, string $title, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->title        = $title;
        $this->createdAt    = $createdAt;
    }

    public function getId(): ForumId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
