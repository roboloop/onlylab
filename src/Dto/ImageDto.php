<?php

namespace App\Dto;

class ImageDto
{
    private $urlPreview;
    private $urlOriginal;
    private $type;

    public function getUrlPreview()
    {
        return $this->urlPreview;
    }

    public function setUrlPreview($urlPreview): self
    {
        $this->urlPreview = $urlPreview;

        return $this;
    }

    public function getUrlOriginal()
    {
        return $this->urlOriginal;
    }

    public function setUrlOriginal($urlOriginal): self
    {
        $this->urlOriginal = $urlOriginal;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }
}
