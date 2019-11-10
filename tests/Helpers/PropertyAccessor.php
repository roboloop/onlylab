<?php

namespace App\Tests\Helpers;

use ReflectionProperty;

class PropertyAccessor
{
    public function get($object, string $property)
    {
        $reflProp = new ReflectionProperty($object, $property);
        $isPrivate = $reflProp->isPrivate();
        if ($isPrivate) {
            $reflProp->setAccessible(true);
        }

        $value = $reflProp->getValue($object);

        if ($isPrivate) {
            $reflProp->setAccessible(false);
        }

        return $value;
    }

    public function set($object, string $property, $value)
    {
        $reflProp = new ReflectionProperty($object, $property);
        $isPrivate = $reflProp->isPrivate();
        if ($isPrivate) {
            $reflProp->setAccessible(true);
        }

        $reflProp->setValue($object, $value);

        if ($isPrivate) {
            $reflProp->setAccessible(false);
        }
    }
}
