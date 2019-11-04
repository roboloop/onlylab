<?php

namespace App\Domain\Enum;

use App\Shared\Domain\ValueObject\Enum;

final class StudioStatus extends Enum
{
    const TYPICAL       = 'typical';
    const BANNED        = 'banned';
    const PREFERABLE    = 'preferable';
}
