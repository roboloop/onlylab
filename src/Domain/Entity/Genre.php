<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity;

use DateTimeImmutable;
use OnlyTracker\Domain\Entity\Enum\GenreStatus;

class Genre
{
    private string $id;
    private string $title;
    private ?string $description;
    private bool $isApproved;
    private GenreStatus $status;
    private DateTimeImmutable $createdAt;

    public function __construct(string $id, string $title, ?string $description, bool $isApproved, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->title        = $title;
        $this->description  = $description;
        $this->isApproved   = $isApproved;
        $this->status       = new GenreStatus(GenreStatus::UNBANNED);
        $this->createdAt    = $createdAt;
    }

    public function getId(): string
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->isApproved;
    }

    public function approve(): void
    {
        $this->isApproved = true;
    }

    public function disapprove(): void
    {
        $this->isApproved = false;
    }

    public function ban()
    {
        $this->status = new GenreStatus(GenreStatus::BANNED);
    }

    public function unban()
    {
        $this->status = new GenreStatus(GenreStatus::UNBANNED);
    }

    public function getStatus(): GenreStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
