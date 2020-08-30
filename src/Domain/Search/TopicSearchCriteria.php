<?php

declare (strict_types=1);

namespace OnlyTracker\Domain\Search;

use Assert\Assert;
use OnlyTracker\Domain\Entity\Enum\StudioStatus;

final class TopicSearchCriteria
{
    private ?array $topicsIds       = null;
    private ?array $forumIds        = null;
    private ?array $rawTitles       = null;
    private ?array $studioIds       = null;
    private ?array $studioUrls      = null;
    private ?array $studioStatuses  = null;
    private ?array $genreIds        = null;
    private ?array $genreTitles     = null;
    private ?bool $isApproved       = null;
    private ?array $titles          = null;
    private ?array $orders          = null;
    private ?array $qualities       = null;
    private ?array $years           = null;
    private int $page               = 1;
    private int $perPage            = 500;

    public static function make()
    {
        return new self;
    }

    /**
     * @param string[]|null $topicIds
     *
     * @return \OnlyTracker\Domain\Search\TopicSearchCriteria
     */
    public function setTopicIds(?array $topicIds): self
    {
        Assert::thatAll($topicIds)->nullOr()->string();

        $this->topicsIds = $topicIds;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getTopicsIds(): ?array
    {
        return $this->topicsIds;
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

    public function getQualities(): ?array
    {
        return $this->qualities;
    }

    public function setQualities(?array $qualities): self
    {
        Assert::thatAll($qualities)->nullOr()->string();

        $this->qualities = $qualities;

        return $this;
    }

    public function getYears(): ?array
    {
        return $this->years;
    }

    public function setYears(?array $years): self
    {
        Assert::thatAll($years)->nullOr()->string();

        $this->years = $years;

        return $this;
    }



    public function addOrderByCreated()
    {
        // $this->orders[] = 'created'

        return $this;
    }

    public function addOrderByTitle()
    {


        return $this;
    }

    public function getOrders(): ?array
    {

    }

    public function setPage(?int $page): self
    {
        if (null === $page) {
            return $this;
        }
        
        if ($page < 1) {
            throw new \InvalidArgumentException('Page cannot be less than 1');
        }
        
        $this->page = $page;
        
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPerPage(?int $perPage): self
    {
        if (null === $perPage) {
            return $this;
        }
        
        if ($perPage < 1) {
            throw new \InvalidArgumentException('Per page cannot be less than 1');
        }
        
        $this->perPage = $perPage;
        
        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
