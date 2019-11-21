<?php

namespace OnlyTracker\Domain\Repository;

use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Shared\RepositoryInterface;
use OnlyTracker\Domain\Shared\IdGeneratorInterface;

interface ForumRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{
    // public function nextIdentity(): ForumId;
}
