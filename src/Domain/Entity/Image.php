<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity;

use OnlyTracker\Domain\Entity\Enum\ImageFormat;
use DateTimeImmutable;

class Image
{
    private string $id;
    private Topic $topic;
    private ImageFormat $format;
    private string $frontUrl;
    private ?string $reference;
    private ?string $original;
    private DateTimeImmutable $createdAt;
    private bool $isBanner;

    public function __construct(string $id, Topic $topic, ImageFormat $format, string $frontUrl, ?string $reference, ?string $original, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->topic        = $topic;
        $this->format       = $format;
        $this->frontUrl     = $frontUrl;
        $this->reference    = $reference;
        $this->original     = $original;
        $this->createdAt    = $createdAt;

        $this->isBanner     = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }

    public function getFormat(): ImageFormat
    {
        return $this->format;
    }

    public function getFrontUrl(): string
    {
        return $this->frontUrl;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getOriginal(): ?string
    {
        return $this->original;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isBanner(): bool
    {
        return $this->isBanner;
    }

    public function markAsBanner(): void
    {
        $this->isBanner = true;
    }
}
