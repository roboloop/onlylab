<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\Enum;

use OnlyTracker\Shared\Domain\ValueObject\Enum;

final class StudioStatus extends Enum
{
    const TYPICAL       = 'typical';
    const BANNED        = 'banned';
    const PREFERABLE    = 'preferable';
}
