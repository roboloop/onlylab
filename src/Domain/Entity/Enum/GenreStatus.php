<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\Enum;

use OnlyTracker\Shared\Domain\ValueObject\Enum;

final class GenreStatus extends Enum
{
    const UNBANNED  = 'unbanned';
    const BANNED    = 'banned';

    const ALL_STATUSES = [
        self::UNBANNED,
        self::BANNED,
    ];

    public static function unbanned()
    {
        return new static(static::UNBANNED);
    }

    public static function banned()
    {
        return new static(static::BANNED);
    }

    public static function all(): array
    {
        return [
            self::UNBANNED,
            self::BANNED,
        ];
    }
}
