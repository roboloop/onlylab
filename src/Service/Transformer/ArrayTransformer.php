<?php

namespace App\Service\Transformer;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ArrayTransformer
{
    public function setKeyFromSource(array $genres, string $keyName, bool $lowerKey = true)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($genres as $genre) {
            $key = $propertyAccessor->getValue($genre, $keyName);

            if ($lowerKey) {
                $key = mb_strtolower($key);
            }

            $result[$key] = $genre;
        }

        return $result ?? [];
    }

    public function toLower(array $data)
    {
        array_walk($data, function (&$value) {
            $value = mb_strtolower($value);
        });

        return $data;
    }
}
