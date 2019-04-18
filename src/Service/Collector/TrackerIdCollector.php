<?php

namespace App\Service\Collector;

class TrackerIdCollector
{
    public function collect(array $dtos)
    {
        foreach ($dtos as $dto) {
            $result[] = (int) $dto->getTrackerId();
        }

        return $result ?? [];
    }
}
