<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $original;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $host;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic", inversedBy="images")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $topic;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBanner = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getOriginal(): ?string
    {
        return $this->original;
    }

    public function setOriginal(?string $original): self
    {
        $this->original = $original;

        return $this;
    }

    public function getHost(): ?int
    {
        return $this->host;
    }

    public function setHost(?int $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getIsBanner(): ?bool
    {
        return $this->isBanner;
    }

    public function setIsBanner(bool $isBanner): self
    {
        $this->isBanner = $isBanner;

        return $this;
    }
}
