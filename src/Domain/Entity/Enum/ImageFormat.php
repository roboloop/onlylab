<?php

declare (strict_types = 1);

namespace App\Domain\Entity\Enum;

use App\Shared\Domain\ValueObject\Enum;

final class ImageFormat extends Enum
{
    const POSTER        = 'poster';
    const SCREENSHOT    = 'screenshot';
    const SCREENLISTING = 'screenlisting';
    const GIF           = 'gif';
    const OTHER         = 'other';

    private static $screenshotNames = [
        'Скриншот',
        'Скриншоты',
        'Screenshots',
        'Примеры',
    ];

    private static $screenListingNames = [
        'Скринлист',
        'ScreenListing',
    ];

    private static $gifNames = [
        'GIF',
    ];

    private static function isScreenshots(string $header): bool
    {
        return self::search($header, self::$screenshotNames);
    }

    private static function isScreenListing(string $header): bool
    {
        return self::search($header, self::$screenListingNames);
    }

    private static function isGif(string $header): bool
    {
        return self::search($header, self::$gifNames);
    }

    private static function search(string $header, array $names)
    {
        foreach ($names as $name) {
            if (false !== mb_eregi($name, $header)) {
                return true;
            }
        }

        return false;
    }

    public static function createFromSpoilerName(string $header)
    {
        if (self::isScreenshots($header)) {
            $format = self::SCREENSHOT;
        } elseif (self::isScreenListing($header)) {
            $format = self::SCREENLISTING;
        } elseif (self::isGif($header)) {
            $format = self::GIF;
        } else {
            $format = self::OTHER;
        }

        return new self($format);
    }
}
