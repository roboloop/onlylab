<?php

namespace App\Infrastructure\Util;

use App\Domain\Shared\DateTimeUtilInterface;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

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

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function ymdHi(string $datetime): DateTimeImmutable
    {
        $datetime = DateTimeImmutable::createFromFormat('Y-m-d H:i', $datetime, new DateTimeZone('UTC'));

        if (false === $datetime) {
            throw new Exception('Cannot create DateTime instance from current "' . $datetime . '" value');
        }

        return $datetime;
    }
}
