<?php

namespace App\Domain\Shared;

use DateTimeImmutable;

interface DateTimeUtilInterface
{
    /**
     * Get current date time instance
     *
     * @return \DateTimeImmutable
     */
    public function now(): DateTimeImmutable;

    /**
     * Get date time instance from Y-m-d H:i format
     *
     * @param string $datetime
     *
     * @return \DateTimeImmutable
     */
    public function ymdHi(string $datetime): DateTimeImmutable;
}
