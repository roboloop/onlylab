<?php

namespace App\Dto;

class RawTopicDto
{
    private $trackerId;
    private $forumId;
    private $forumTitle;
    private $rawTitle;
    private $size;
    private $trackerCreatedAt;
    private $authorId;
    private $authorName;
    private $images = [];

    public function getTrackerId()
    {
        return $this->trackerId;
    }

    public function setTrackerId($trackerId): self
    {
        $this->trackerId = $trackerId;
        
        return $this;
    }

    public function getForumId()
    {
        return $this->forumId;
    }

    public function setForumId($forumId): self
    {
        $this->forumId = $forumId;

        return $this;
    }

    public function getForumTitle()
    {
        return $this->forumTitle;
    }

    public function setForumTitle($forumTitle): self
    {
        $this->forumTitle = $forumTitle;

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

    public function getImages()
    {
        return $this->images;
    }

    public function addImages($images): self
    {
        if (is_array($images)) {
            $this->images = array_merge($this->images, $images);
        } else {
            $this->images[] = $images;
        }

        return $this;
    }
}
