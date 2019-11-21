<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity;

use OnlyTracker\Domain\Shared\UniqueIdentityInterface;
use DateTimeImmutable;

class Forum implements UniqueIdentityInterface
{
    private $id;
    private $exId;
    private $title;
    private $createdAt;

    public function __construct(string $id, int $exId, string $title, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->exId         = $exId;
        $this->title        = $title;
        $this->createdAt    = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getExId(): int
    {
        return $this->exId;
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
