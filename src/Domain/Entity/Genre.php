<?php

declare (strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Shared\UniqueIdentityInterface;
use DateTimeImmutable;

class Genre implements UniqueIdentityInterface
{
    private $id;
    private $title;
    private $description;
    private $isApproved;
    private $createdAt;

    public function __construct(string $id, string $title, string $description, bool $isApproved, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->title        = $title;
        $this->description  = $description;
        $this->isApproved   = $isApproved;
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

    public function getDescription(): string
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

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
