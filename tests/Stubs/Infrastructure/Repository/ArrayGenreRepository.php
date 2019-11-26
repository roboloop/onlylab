<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure\Repository;

use OnlyTracker\Domain\Identity\GenreId;
use OnlyTracker\Domain\Repository\GenreRepositoryInterface;
use OnlyTracker\Shared\Domain\ValueObject\Uuid;

class ArrayGenreRepository extends ArrayRepository implements GenreRepositoryInterface
{
    public function nextIdentity(): GenreId
    {
        return new GenreId(Uuid::random());
    }
}
