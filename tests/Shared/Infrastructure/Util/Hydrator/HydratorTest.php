<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Shared\Infrastructure\Util\Hydrator;

use OnlyTracker\Shared\Infrastructure\Util\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    /** @var \OnlyTracker\Shared\Infrastructure\Util\Hydrator\Hydrator */
    private $hydrator;

    protected function setUp()
    {
        $this->hydrator = new Hydrator;
    }

    /**
     * @dataProvider data
     */
    public function testHydrate($data, $className, $expected)
    {
        $output = $this->hydrator->hydrate($data, $className);

        $this->assertEquals($expected, $output);
    }

    public function data()
    {
        $a = new A();
        $a->attr1 = 'value1';
        $a->attr2 = 2;
        $a->attr3 = 3.0;
        yield [
            [
                'attr1' => 'value1',
                'attr2' => 2,
                'attr3' => 3.0,
            ],
            A::class,
            $a
        ];
    }
}
