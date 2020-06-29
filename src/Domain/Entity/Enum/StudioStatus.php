<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\Enum;

use OnlyTracker\Shared\Domain\ValueObject\Enum;

final class StudioStatus extends Enum
{
    const TYPICAL       = 'typical';
    const BANNED        = 'banned';
    const PREFERABLE    = 'preferable';

    public static function typical()
    {
        return new static(static::TYPICAL);
    }

    public static function banned()
    {
        return new static(static::BANNED);
    }

    public static function preferable()
    {
        return new static(static::PREFERABLE);
    }

    public static function all(): array
    {
        return [
            self::TYPICAL,
            self::PREFERABLE,
            self::BANNED,
        ];
    }
}
