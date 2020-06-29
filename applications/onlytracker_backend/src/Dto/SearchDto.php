<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Dto;

use OnlyTracker\Infrastructure\Symfony\Constraints as AppConstraints;
use OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\IncomingDataInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchDto implements IncomingDataInterface
{
    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private $forums;

    /**
     * @AppConstraints\QueryString()
     */
    private $genres;

    /**
     * @AppConstraints\QueryString()
     */
    private $studios;

    /**
     * @AppConstraints\QueryString()
     */
    private $title;

    /**
     * @AppConstraints\QueryString()
     */
    private $rawTitle;

    /**
     * @AppConstraints\QueryString()
     */
    private $years;

    /**
     * @AppConstraints\QueryString()
     */
    private $qualities;

    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Choice({
     *         \OnlyTracker\Domain\Entity\Enum\StudioStatus::TYPICAL,
     *         \OnlyTracker\Domain\Entity\Enum\StudioStatus::BANNED,
     *         \OnlyTracker\Domain\Entity\Enum\StudioStatus::PREFERABLE
     *     })
     * })
     */
    private $studioStatuses;

    public function forums()
    {
        return $this->forums;
    }

    public function genres()
    {
        return $this->genres;
    }

    public function studios()
    {
        return $this->studios;
    }

    public function title()
    {
        return $this->title;
    }

    public function rawTitle()
    {
        return $this->rawTitle;
    }

    public function years()
    {
        return $this->years;
    }

    public function qualities()
    {
        return $this->qualities;
    }

    public function studioStatuses()
    {
        return $this->studioStatuses;
    }
}
