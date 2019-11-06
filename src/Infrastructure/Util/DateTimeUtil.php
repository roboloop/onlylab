<?php

namespace App\Infrastructure\Util;

use App\Domain\Shared\DateTimeUtilInterface;
use DateTimeImmutable;
use DateTimeZone;

class DateTimeUtil implements DateTimeUtilInterface
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
