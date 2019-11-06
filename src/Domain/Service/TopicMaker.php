<?php

namespace App\Domain\Service;

use App\Domain\Entity\Forum;
use App\Domain\Factory\GenreFactory;
use App\Domain\Factory\StudioFactory;
use App\Domain\Factory\TopicFactory;

class TopicMaker
{
    private $topicFactory;
    private $genreFactory;
    private $studioFactory;

    public function __construct(TopicFactory $topicFactory, GenreFactory $genreFactory, StudioFactory $studioFactory)
    {
        $this->topicFactory     = $topicFactory;
        $this->genreFactory     = $genreFactory;
        $this->studioFactory    = $studioFactory;
    }

    public function makeNotLoadedTopic(Forum $forum)
    {
        // TODO:
    }
}
