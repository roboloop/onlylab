<?php

namespace App\Infrastructure\Util;

class SizeConverter
{
    private static $base = 1024;

    private static $suffixes = [
        'TB'    => 4,
        'GB'    => 3,
        'MB'    => 2,
        'KB'    => 1,
        'B'     => 0,
    ];

    /**
     * @param string $size
     *
     * @return int|null
     */
    public function fromStringToInt(string $size)
    {
        preg_match('~(?P<quantity>[\d]+\.?\d+)\s*(?P<unit>.*)$~', trim($size), $matches);

        if (isset($matches['unit']) and isset(self::$suffixes[$matches['unit']]) and isset($matches['quantity']))
            return (int) ($matches['quantity'] * self::$base ** self::$suffixes[$matches['unit']]);

        return null;
    }
}
