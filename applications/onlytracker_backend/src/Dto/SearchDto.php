<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Dto;

use OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\IncomingDataInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchDto implements IncomingDataInterface
{
    /**
     * @Assert\NotBlank()
     */
    private $forumIds;
    private $title;
    private $rawTitles;
    private $studioUrls;
    private $studioStatuses;
    private $genreTitles;
    private $isApproved;
    private $qualities;
    private $years;

    public function __construct()
    {
    }

    public function forumIds()
    {
        return $this->forumIds;
    }

    public function title()
    {
        return $this->title;
    }

    public function rawTitles()
    {
        return $this->rawTitles;
    }

    public function studioUrls()
    {
        return $this->studioUrls;
    }

    public function studioStatuses()
    {
        return $this->studioStatuses;
    }

    public function genreTitles()
    {
        return $this->genreTitles;
    }

    public function isApproved()
    {
        return $this->isApproved;
    }

    public function qualities()
    {
        return $this->qualities;
    }

    public function years()
    {
        return $this->years;
    }
}
