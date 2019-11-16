<?php

namespace App\Application\Dto;

class RawTopicDto
{
    private $exId;
    private $rawTitle;
    private $size;
    private $exCreatedAt;
    private $images;
    private $forum;

    public function __construct($exId, $rawTitle, $size, $exCreatedAt, $forum, $images = [])
    {
        $this->exId         = $exId;
        $this->rawTitle     = $rawTitle;
        $this->size         = $size;
        $this->exCreatedAt  = $exCreatedAt;
        $this->forum        = $forum;
        $this->images       = $images;
    }

    public function getExId()
    {
        return $this->exId;
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

    public function getForum()
    {
        return $this->forum;
    }

    public function getImages()
    {
        return $this->images;
    }
}
