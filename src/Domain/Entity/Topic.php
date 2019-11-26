<?php

namespace OnlyTracker\Domain\Entity;

use OnlyTracker\Domain\Entity\Embeddable\ParsedTitle;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class Topic
{
    private $id;
    private $exId;
    private $forum;
    private $parsedTitle;
    private $size;
    private $exCreatedAt;
    private $createdAt;
    private $images;
    private $genres;
    private $studios;
    private $isLoaded;

    public function __construct(string $id, int $exId, ParsedTitle $parsedTitle, Forum $forum, ?int $size, ?DateTimeImmutable $exCreatedAt, DateTimeImmutable $createdAt, bool $isLoaded)
    {
        $this->id           = $id;
        $this->exId         = $exId;
        $this->parsedTitle  = $parsedTitle;
        $this->forum        = $forum;
        $this->size         = $size;
        $this->exCreatedAt  = $exCreatedAt;
        $this->createdAt    = $createdAt;
        $this->isLoaded     = $isLoaded;

        $this->images   = new ArrayCollection;
        $this->genres   = new ArrayCollection;
        $this->studios  = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getExId(): int
    {
        return $this->exId;
    }

    public function getForum(): Forum
    {
        return $this->forum;
    }

    public function getParsedTitle(): ParsedTitle
    {
        return $this->parsedTitle;
    }

    public function setParsedTitle(ParsedTitle $parsedTitle): self
    {
        $this->parsedTitle = $parsedTitle;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getExCreatedAt(): ?DateTimeImmutable
    {
        return $this->exCreatedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isLoaded(): bool
    {
        return $this->isLoaded;
    }

    public function makeAsLoaded(): void
    {
        $this->isLoaded = true;
    }

    public function getImages(): array
    {
        return $this->images->toArray();
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function getGenres(): array
    {
        return $this->genres->toArray();
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function getStudios(): array
    {
        return $this->studios->toArray();
    }

    public function addStudio(Studio $studio): self
    {
        if (!$this->studios->contains($studio)) {
            $this->studios->add($studio);
        }

        return $this;
    }
}
