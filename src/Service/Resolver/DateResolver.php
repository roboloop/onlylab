<?php

namespace App\Resolver;

use DateTime;

class DateResolver
{
    public function resolve($date)
    {
        // TODO: if unix - one logic
        // TODO: if dummy data, use mask

        return new DateTime();
    }
}
