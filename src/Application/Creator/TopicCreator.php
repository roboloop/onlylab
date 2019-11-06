<?php

namespace App\Application\Creator;

use App\Dto\RawTopicDto;

class TopicCreator
{
    public function createFromDto(RawTopicDto $dto)
    {
        $dto->getForumId();
    }
}
