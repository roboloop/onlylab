<?php

namespace App\Service\Manager;

class SleepManager
{
    public function perRequest()
    {
        usleep(rand(5000000, 7000000));
    }
}
