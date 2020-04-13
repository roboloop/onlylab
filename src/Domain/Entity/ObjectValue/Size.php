<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\ObjectValue;

use InvalidArgumentException;

final class Size
{
    private static int $base = 1024;

    private static array $suffixes = [
        'TB'    => 4,
        'GB'    => 3,
        'MB'    => 2,
        'KB'    => 1,
        'B'     => 0,
    ];

    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Size cannot be negative');
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->toString();
    }

    private function toString(): string
    {
        $exp = (int) floor(log($this->value, 1024));

        if (in_array($exp, static::$suffixes, true)) {
            return number_format($this->value / pow(1024, $exp), 2) . array_search($exp, static::$suffixes, true);
        }

        return (string) $this->value . 'B';
    }

    public static function createFromString(string $size): self
    {
        if (preg_match('#^\s*(?P<quantity>\d+(?:\.\d+)?)\s*(?P<unit>TB|GB|MB|KB|B)\s*$#iu', $size, $matches)) {
            $quantity   = $matches['quantity'];
            $unit       = strtoupper($matches['unit']);
            return new self((int) ($quantity * self::$base ** self::$suffixes[$unit]));
        }

        throw new InvalidArgumentException(sprintf('Size cannot be created from: "%s"', $size));
    }

    public function __toString()
    {
        return $this->toString();
    }
}
