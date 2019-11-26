<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Identity\ImageId;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;

class ArrayImageRepository extends ArrayRepository implements ImageRepositoryInterface
{
    public function nextIdentity(): ImageId
    {
        return new ImageId(Uuid::random());
    }
}