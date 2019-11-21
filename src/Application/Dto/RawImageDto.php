<?php

namespace OnlyTracker\Application\Dto;

class RawImageDto
{
    const PLACE_ON_PAGE         = 1;
    const PLACE_UNDER_SPOILER   = 2;

    private $frontUrl;
    private $reference;
    private $place;
    private $spoilerName;

    public function __construct($frontUrl, $reference, $place, $spoilerName = null)
    {
        $this->frontUrl     = $frontUrl;
        $this->reference    = $reference;
        $this->place        = $place;
        $this->spoilerName  = $spoilerName;
    }

    public function getFrontUrl()
    {
        return $this->frontUrl;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function getSpoilerName()
    {
        return $this->spoilerName;
    }
}
