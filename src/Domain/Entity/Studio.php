<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Identity\StudioId;
use DateTimeImmutable;

class Studio
{
    private $id;
    private $url;
    private $status;
    private $createdAt;

    public function __construct(StudioId $id, string $url, StudioStatus $status, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->url          = $url;
        $this->status       = $status;
        $this->createdAt    = $createdAt;
    }

    public function getId(): StudioId
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

    public function ban()
    {
        $this->status = new StudioStatus(StudioStatus::BANNED);
    }

    public function unban()
    {
        $this->status = new StudioStatus(StudioStatus::TYPICAL);
    }

    public function prefer()
    {
        $this->status = new StudioStatus(StudioStatus::PREFERABLE);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
