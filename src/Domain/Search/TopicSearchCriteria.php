<?php

declare (strict_types=1);

namespace OnlyTracker\Domain\Search;

use Assert\Assert;
use OnlyTracker\Domain\Entity\Enum\StudioStatus;

class TopicSearchCriteria
{
    private $forumIds;
    private $rawTitles;
    private $studioIds;
    private $studioUrls;
    private $studioStatuses;
    private $genreIds;
    private $genreTitles;
    private $isApproved;
    private $titles;

    public static function make()
    {
        return new self;
    }

    /**
     * @param string[]|null $forumIds
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setForumIds(?array $forumIds): self
    {
        Assert::thatAll($forumIds)->nullOr()->string();

        $this->forumIds = $forumIds;

        return $this;
    }

    /**
     * @param string[]|null $rawTitles
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setRawTitles(?array $rawTitles): self
    {
        Assert::thatAll($rawTitles)->nullOr()->string();

        $this->rawTitles = $rawTitles;

        return $this;
    }

    /**
     * @param string[]|null $ids
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setStudioIds(?array $ids): self
    {
        Assert::thatAll($ids)->nullOr()->string();

        $this->studioIds = $ids;

        return $this;
    }

    /**
     * @param string[]|null $urls
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setStudioUrls(?array $urls): self
    {
        Assert::thatAll($urls)->nullOr()->string();

        $this->studioUrls = $urls;

        return $this;
    }

    /**
     * @param \OnlyTracker\Domain\Entity\Enum\StudioStatus[]|null $statuses
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setStudioStatuses(?array $statuses): self
    {
        Assert::thatAll($statuses)->nullOr()->isInstanceOf(StudioStatus::class);

        $this->studioStatuses = $statuses;

        return $this;
    }

    /**
     * @param string[]|null $ids
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setGenreIds(?array $ids): self
    {
        Assert::thatAll($ids)->nullOr()->string();

        $this->genreIds = $ids;

        return $this;
    }

    /**
     * @param string[]|null $titles
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setGenreTitles(?array $titles): self
    {
        Assert::thatAll($titles)->nullOr()->string();

        $this->genreTitles = $titles;

        return $this;
    }

    public function setIsApproved(?bool $isApproved): self
    {
        Assert::that($isApproved)->nullOr()->boolean();

        $this->isApproved = $isApproved;

        return $this;
    }

    public function setTitles(?array $titles): self
    {
        Assert::thatAll($titles)->nullOr()->string();

        $this->titles = $titles;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getForumIds(): ?array
    {
        return $this->forumIds;
    }

    /**
     * @return string[]|null
     */
    public function getRawTitles(): ?array
    {
        return $this->rawTitles;
    }

    /**
     * @return string[]|null
     */
    public function getStudioIds(): ?array
    {
        return $this->studioIds;
    }

    /**
     * @return string[]|null
     */
    public function getStudioUrls(): ?array
    {
        return $this->studioUrls;
    }

    /**
     * @return StudioStatus[]|null
     */
    public function getStudioStatuses(): ?array
    {
        return $this->studioStatuses;
    }

    /**
     * @return string[]|null
     */
    public function getGenreIds(): ?array
    {
        return $this->genreIds;
    }

    /**
     * @return string[]null
     */
    public function getGenreTitles(): ?array
    {
        return $this->genreTitles;
    }

    /**
     * @return bool|null
     */
    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    /**
     * @return array|null
     */
    public function getTitles(): ?array
    {
        return $this->titles;
    }
}
