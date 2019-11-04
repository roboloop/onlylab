<?php

declare (strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Enum\StudioStatus;
use DateTimeImmutable;

class Studio
{
    private $id;
    private $url;
    private $status;
    private $createdAt;

    public function __construct(string $id, string $url, StudioStatus $status, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->url          = $url;
        $this->status       = $status;
        $this->createdAt    = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStatus(): StudioStatus
    {
        return $this->status;
    }

    public function setStatus(StudioStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
