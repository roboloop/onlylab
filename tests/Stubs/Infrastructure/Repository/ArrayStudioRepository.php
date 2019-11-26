<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Identity\StudioId;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;

class ArrayStudioRepository extends ArrayRepository implements StudioRepositoryInterface
{
    public function nextIdentity(): StudioId
    {
        return new StudioId(Uuid::random());
    }
}