<?php

declare (strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Enum\ImageFormat;
use DateTimeImmutable;

class Image
{
    private $id;
    private $topic;
    private $format;
    private $preview;
    private $reference;
    private $original;
    private $host;
    private $createdAt;
    private $isBanner;

    public function __construct(string $id, Topic $topic, ImageFormat $format, string $preview, string $reference, string $original, string $host, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->topic        = $topic;
        $this->format       = $format;
        $this->preview      = $preview;
        $this->reference    = $reference;
        $this->original     = $original;
        $this->host         = $host;
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

    public function getPreview(): string
    {
        return $this->preview;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getOriginal(): string
    {
        return $this->original;
    }

    public function getHost(): string
    {
        return $this->host;
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
