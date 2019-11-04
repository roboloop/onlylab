<?php

namespace App\Domain\Enum;

use App\Shared\Domain\ValueObject\Enum;

final class ImageFormat extends Enum
{
    const POSTER        = 'poster';
    const SCREENSHOT    = 'screenshot';
    const SCREENLISTING = 'screenlisting';
    const GIF           = 'gif';
    const OTHER         = 'other';
}
