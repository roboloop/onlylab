<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Infrastructure\Util;

use OnlyTracker\Infrastructure\Util\ObjectArrayModifier;
use PHPUnit\Framework\TestCase;

class ObjectArrayModifierTest extends TestCase
{
    /**
     * @dataProvider dataGroupByFirstLetter
     */
    public function testGroupByFirstLetter($input, $callable, $expected)
    {
        $result = ObjectArrayModifier::groupByFirstLetter($input, $callable);

        $this->assertSame($expected, $result);
    }

    public function dataGroupByFirstLetter()
    {
        return [
            [
                [], null, []
            ],
            [
                // urls
                [
                    'some.ru',
                    'example.ru',
                    'Some.com',
                    '',
                    '21site.sh',
                    'bar',
                    '-site.ru',
                    '+site.ru'
                ],
                // callable
                null,
                // expected
                [
                    '0-9' => [
                        '21site.sh',
                    ],
                    'E' => [
                        'example.ru',
                    ],
                    'S' => [
                        'Some.com',
                        'some.ru',
                    ],
                    'Other' => [
                        '',
                        '+site.ru',
                        '-site.ru',
                        'bar',
                    ],
                ],
            ],
            [
                // urls
                [
                    $dummy1 = new DummyStringClass('some.ru'),
                    $dummy2 = new DummyStringClass('example.ru'),
                    $dummy3 = new DummyStringClass('Some.com'),
                    $dummy4 = new DummyStringClass(''),
                    $dummy5 = new DummyStringClass('21site.sh'),
                    $dummy6 = new DummyStringClass('bar'),
                    $dummy7 = new DummyStringClass('-site.ru'),
                    $dummy8 = new DummyStringClass('+site.ru'),
                ],
                // callable
                function (DummyStringClass $value) {
                    return $value->getValue();
                },
                [
                    '0-9' => [
                        $dummy5,
                    ],
                    'E' => [
                        $dummy2,
                    ],
                    'S' => [
                        $dummy3,
                        $dummy1,
                    ],
                    'Other' => [
                        $dummy4,
                        $dummy8,
                        $dummy7,
                        $dummy6,
                    ],
                ],
            ],
        ];
    }
}

class DummyStringClass
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
