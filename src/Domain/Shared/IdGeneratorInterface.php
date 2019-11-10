<?php

namespace App\Domain\Shared;

interface IdGeneratorInterface
{
    /**
     * @return string
     */
    public function nextIdentity();
}
