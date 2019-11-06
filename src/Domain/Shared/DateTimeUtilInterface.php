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
}
