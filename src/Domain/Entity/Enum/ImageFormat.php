<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\Enum;

use OnlyTracker\Shared\Domain\ValueObject\Enum;

final class ImageFormat extends Enum
{
    const POSTER        = 'poster';
    const SCREENSHOT    = 'screenshot';
    const SCREENLISTING = 'screenlisting';
    const GIF           = 'gif';
    const OTHER         = 'other';
    const PHOTOSET      = 'photoset';

    private static $screenshotNames = [
        'Скриншот',
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

    private static $photosetNames = [
        'Фотосет',
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

    public static function isPhotoset(string $header): bool
    {
        return self::search($header, self::$photosetNames);
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
        if (self::isPhotoset($header)) {
            $format = self::PHOTOSET;
        } elseif (self::isScreenshots($header)) {
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
