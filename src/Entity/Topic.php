<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 * @ORM\Table(name="topics")
 */
class Topic
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $studio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quality;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $trackerCreatedAt;

    /**
     * @ORM\Column(type="integer", unique=true))
     */
    private $trackerId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releaseAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Forum", inversedBy="topics")
     * @ORM\JoinColumn(nullable=true)
     */
    private $forum;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Studio", inversedBy="topics")
     */
    private $studios;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="topics")
     */
    private $genres;

    public function __construct()
    {
        $this->studios  = new ArrayCollection();
        $this->genres   = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStudio(): ?string
    {
        return $this->studio;
    }

    public function setStudio(?string $studio): self
    {
        $this->studio = $studio;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getQuality(): ?string
    {
        return $this->quality;
    }

    public function setQuality(?string $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getTrackerCreatedAt(): ?\DateTimeInterface
    {
        return $this->trackerCreatedAt;
    }

    public function setTrackerCreatedAt(?\DateTimeInterface $trackerCreatedAt): self
    {
        $this->trackerCreatedAt = $trackerCreatedAt;

        return $this;
    }

    public function getTrackerId(): ?int
    {
        return $this->trackerId;
    }

    public function setTrackerId(int $trackerId): self
    {
        $this->trackerId = $trackerId;

        return $this;
    }

    public function getReleaseAt(): ?\DateTimeInterface
    {
        return $this->releaseAt;
    }

    public function setReleaseAt(?\DateTimeInterface $releaseAt): self
    {
        $this->releaseAt = $releaseAt;

        return $this;
    }

    public function getForum(): ?Forum
    {
        return $this->forum;
    }

    public function setForum(?Forum $forum): self
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * @return Collection|Studio[]
     */
    public function getStudios(): Collection
    {
        return $this->studios;
    }

    public function addStudio(Studio $studio): self
    {
        if (!$this->studios->contains($studio)) {
            $this->studios[] = $studio;
        }

        return $this;
    }

    public function removeStudio(Studio $studio): self
    {
        if ($this->studios->contains($studio)) {
            $this->studios->removeElement($studio);
        }

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }
}
