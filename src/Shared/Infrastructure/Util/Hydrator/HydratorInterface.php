<?php

namespace OnlyTracker\Shared\Infrastructure\Util\Hydrator;

interface HydratorInterface
{
    /**
     * Hydration process
     *
     * @param array  $data
     * @param string $className
     *
     * @return object
     */
    public function hydrate(array $data, string $className);
}
