<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Domain\Entity\ObjectValue;

use InvalidArgumentException;
use OnlyTracker\Domain\Entity\ObjectValue\Size;
use PHPUnit\Framework\TestCase;

class SizeTest extends TestCase
{
    /**
     * @dataProvider validData
     */
    public function testCreateFromString($value, $expected)
    {
        $output = Size::createFromString($value);

        $this->assertEquals($expected, $output->value());
    }

    /**
     * @dataProvider invalidData
     */
    public function testFailCreateFromString($value)
    {
        try {
            Size::createFromString($value);
            $this->fail(sprintf('Should failed with value: "%s"', $value));
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf('Size cannot be created from: "%s"', $value), $e->getMessage());
        }
    }

    public function validData()
    {
        return [
            ['0 B', 0],
            ['17931 B', 17931],
            ['0061612 B', 61612],
            ['56.5KB', 57856],
            ['5GB', 5368709120],
            ['  5.5   MB  ', 5767168],
            ['1.5 KB', 1536],
            ['1.5 MB', 1572864],
            ['1.5 GB', 1610612736],
            ['1.5 TB', 1649267441664],
        ];
    }

    public function invalidData()
    {
        return [
            ['2Bk'],
            ['15,1 KB'],
            ['5. KB'],
            ['A. KB'],
            ['A KB'],
            ['a.b GB'],
            ['6.5K'],
            ['4.7K KB'],
            ['5.1 MB 51'],
            ['5 MB 4 KB'],
            ['5MB 4KB'],
            ['5 .5 KB'],
            ['5. 1 KB'],
        ];
    }
}
