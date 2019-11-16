<?php

namespace App\Application\Dto;

class RawForumDto
{
    private $exId;
    private $title;

    public function __construct($exId, $title)
    {
        $this->exId  = $exId;
        $this->title = $title;
    }

    public function getExId()
    {
        return $this->exId;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
