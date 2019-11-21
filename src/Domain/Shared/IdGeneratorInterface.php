<?php

namespace OnlyTracker\Domain\Shared;

interface IdGeneratorInterface
{
    /**
     * @return string
     */
    public function nextIdentity();
}
