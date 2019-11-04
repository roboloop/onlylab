<?php

declare (strict_types = 1);

namespace App\Domain\Entity;

use DateTimeImmutable;

class ExUser
{
    private $id;
    private $exId;
    private $name;
    private $createdAt;

    public function __construct(string $id, int $exId, string $name, DateTimeImmutable $createdAt)
    {
        $this->id           = $id;
        $this->exId         = $exId;
        $this->name         = $name;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
