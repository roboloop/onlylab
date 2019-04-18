<?php

namespace App\Service\Collector;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ValueCollector
{
    public function collect(array $dtos, $field)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($dtos as $dto) {
            $result[] = $propertyAccessor->getValue($dto, $field);
        }

        return $result ?? [];
    }
}
