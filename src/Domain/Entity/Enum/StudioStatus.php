<?php

declare (strict_types = 1);

namespace App\Domain\Entity\Enum;

use App\Shared\Domain\ValueObject\Enum;

final class StudioStatus extends Enum
{
    const TYPICAL       = 'typical';
    const BANNED        = 'banned';
    const PREFERABLE    = 'preferable';
}
