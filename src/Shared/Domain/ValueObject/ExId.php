<?php

namespace OnlyTracker\Shared\Domain\ValueObject;

abstract class ExId
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public static function random()
    {
        return new static(rand(1, INF));
    }

    public function __toString()
    {
        return (string) $this->value();
    }
}
