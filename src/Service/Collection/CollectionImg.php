<?php

namespace App\Service\Collection;

use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class CollectionImg
{
    protected $collection;
    protected $field;

    public function set($key, $value)
    {
        $this->collection[$key] = $value;
    }

    public function get($key)
    {
        return $this->collection[$key];
    }

    public function isExists($key)
    {
        return isset($this->collection[$key]);
    }

    public function intersectWithRawData(array &$rawData, bool $keyToLower = true)
    {
        foreach ($rawData as $key => $raw) {
            $lowerKey = $keyToLower ? mb_strtolower($raw) : $raw;
            if (array_key_exists($lowerKey, $this->collection)) {
                $exists[] = $this->collection[$lowerKey];
                unset($rawData[$key]);
            }
        }

        return $exists ?? [];
    }

    public function add($entity, bool $keyToLower = true)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $value = $propertyAccessor->getValue($entity, $this->field);
        $lowerKey = ($keyToLower and is_string($value)) ? mb_strtolower($value) : $value;
        $this->collection[$lowerKey] = $entity;
    }

    public function addMany(array $entities, bool $keyToLower = true)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($entities as $entity) {
            $value = $propertyAccessor->getValue($entity, $this->field);
            $lowerKey = ($keyToLower and is_string($value)) ? mb_strtolower($value) : $value;
            $this->collection[$lowerKey] = $entity;
        }
    }
}
