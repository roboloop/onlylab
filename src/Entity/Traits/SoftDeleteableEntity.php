<?php

namespace App\Entity\Traits;

trait SoftDeleteableEntity
{
    /**
     * Timestamp of entity deletion.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt = null): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isDeleted()
    {
        return null !== $this->deletedAt;
    }
}
