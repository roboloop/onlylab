<?php

namespace App\Domain\Repository;

use App\Domain\Identity\ForumId;
use App\Domain\Shared\RepositoryInterface;
use App\Domain\Shared\IdGeneratorInterface;

interface ForumRepositoryInterface extends RepositoryInterface, IdGeneratorInterface
{
    // public function nextIdentity(): ForumId;
}
