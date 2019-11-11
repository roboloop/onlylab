<?php

namespace App\Application\Dto;

class RawTopicDto
{
    private $exId;
    private $forumExId;
    private $forumTitle;
    private $rawTitle;
    private $size;
    private $exCreatedAt;
    private $images;

    public function __construct($exId, $forumExId, $forumTitle, $rawTitle, $size, $exCreatedAt, $images) {
        $this->exId         = $exId;
        $this->forumExId    = $forumExId;
        $this->forumTitle   = $forumTitle;
        $this->rawTitle     = $rawTitle;
        $this->size         = $size;
        $this->exCreatedAt  = $exCreatedAt;
        $this->images       = $images;
    }

    public function getExId()
    {
        return $this->exId;
    }

    public function getForumExId()
    {
        return $this->forumExId;
    }

    public function getForumTitle()
    {
        return $this->forumTitle;
    }

    public function getRawTitle()
    {
        return $this->rawTitle;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getExCreatedAt()
    {
        return $this->exCreatedAt;
    }

    public function getImages()
    {
        return $this->images;
    }
}
