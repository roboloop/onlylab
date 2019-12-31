<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Dto;

use OnlyTracker\Infrastructure\Symfony\ArgumentResolver\IncomingDataInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class SearchDto implements IncomingDataInterface
{
//    /**
//     * @var string[]
//     * @Assert\String
//     * @ConvertArray("OnlyTracker\Domain\Identity\ForumId")
//     */

    private $forumIds;
    private $rawTitles;
    private $studioIds;
    private $studioUrls;
    private $studioStatuses;
    private $genreIds;
    private $genreTitles;
    private $isApproved;

    public function forumIds()
    {
        return $this->forumIds;
    }

    public function rawTitles()
    {
        return $this->rawTitles;
    }

    public function studioIds()
    {
        return $this->studioIds;
    }

    public function studioUrls()
    {
        return $this->studioUrls;
    }

    public function studioStatuses()
    {
        return $this->studioStatuses;
    }

    public function genreIds()
    {
        return $this->genreIds;
    }

    public function genreTitles()
    {
        return $this->genreTitles;
    }

    public function isApproved()
    {
        return $this->isApproved;
    }
}
