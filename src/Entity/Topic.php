<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $title;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Forum", inversedBy="topics", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $forum;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Studio", inversedBy="topics", cascade={"persist"})
     * @ORM\JoinTable(name="studio_topic")
     */
    private $studios;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="topics", cascade={"persist"})
     * @ORM\JoinTable(name="genre_topic")
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="topic", cascade={"persist", "remove"})
     *
     */
    private $images;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $rawTitle;

    public function __construct()
    {
        $this->studios  = new ArrayCollection();
        $this->genres   = new ArrayCollection();
        $this->images   = new ArrayCollection();
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

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTopic($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTopic() === $this) {
                $image->setTopic(null);
            }
        }

        return $this;
    }

    public function getRawTitle(): ?string
    {
        return $this->rawTitle;
    }

    public function setRawTitle(string $rawTitle): self
    {
        $this->rawTitle = $rawTitle;

        return $this;
    }
}
