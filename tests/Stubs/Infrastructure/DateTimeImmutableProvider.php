<?php

namespace OnlyTracker\Tests\Stubs\Infrastructure;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

class DateTimeImmutableProvider
{
    public function dateTimeImmutable($max = 'now', $timezone = null)
    {
        $dateTime = new DateTimeImmutable($max, new DateTimeZone($timezone ?: 'UTC'));

        if (false === $dateTime) {
            throw new InvalidArgumentException;
        }

        return $dateTime;
    }
}
