<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Domain\ValueObject;

use InvalidArgumentException;
use ReflectionClass;

abstract class Enum
{
    protected $value;
    
    public function __construct($value)
    {
        $this->errorIfNotAcceptable($value);
        
        $this->value = $value;
    }

    private function errorIfNotAcceptable($value)
    {
        $reflected = new ReflectionClass(static::class);

        if (!in_array($value, $reflected->getConstants(), true)) {
            throw new InvalidArgumentException(sprintf('The <%s> value is not a valid enum-value', $value));
        }
    }

    public function equals(Enum $other)
    {
        return $this->value === $other->value;
    }

    public function value()
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
