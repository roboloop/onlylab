<?php

declare (strict_types = 1);

namespace OnlyTracker\Domain\Entity\ObjectValue;

use InvalidArgumentException;

final class Size
{
    private static $base = 1024;

    private static $suffixes = [
        'TB'    => 4,
        'GB'    => 3,
        'MB'    => 2,
        'KB'    => 1,
        'B'     => 0,
    ];

    private $value;

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

    public static function createFromString(string $size)
    {
        preg_match('~(?P<quantity>[\d]+\.?\d+)\s*(?P<unit>.*)$~u', trim($size), $matches);

        if (isset($matches['unit']) and isset(self::$suffixes[$matches['unit']]) and isset($matches['quantity'])) {
            return new self((int) ($matches['quantity'] * self::$base ** self::$suffixes[$matches['unit']]));
        }

        throw new InvalidArgumentException('Size cannot be created');
    }
}
