<?php

namespace App\Dto;

class ForumLineDto
{
    private $trackerId;
    private $rawTitle;
    private $size;
    private $trackerCreatedAt;
    private $authorId;
    private $authorName;

    public function getTrackerId()
    {
        return $this->trackerId;
    }

    public function setTrackerId($trackerId): self
    {
        $this->trackerId = $trackerId;
        
        return $this;
    }

    public function getRawTitle()
    {
        return $this->rawTitle;
    }

    public function setRawTitle($rawTitle): self
    {
        $this->rawTitle = $rawTitle;
        
        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size): self
    {
        $this->size = $size;
        
        return $this;
    }

    public function getTrackerCreatedAt()
    {
        return $this->trackerCreatedAt;
    }

    public function setTrackerCreatedAt($trackerCreatedAt): self
    {
        $this->trackerCreatedAt = $trackerCreatedAt;
        
        return $this;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setAuthorId($authorId): self
    {
        $this->authorId = $authorId;
        
        return $this;
    }

    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function setAuthorName($authorName): self
    {
        $this->authorName = $authorName;
        
        return $this;
    }
}
