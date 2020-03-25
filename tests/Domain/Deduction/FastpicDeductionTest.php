<?php

declare (strict_types=1);

namespace OnlyTracker\Tests\Domain\Deduction;

use OnlyTracker\Domain\Deduction\FastpicDeduction;
use PHPUnit\Framework\TestCase;

class FastpicDeductionTest extends TestCase
{
    private FastpicDeduction $fastpicDeduction;

    protected function setUp()
    {
        $this->fastpicDeduction = new FastpicDeduction;
    }

    /**
     * @dataProvider dataDeduct
     */
    public function testDeduct($frontUrl, $expected)
    {
        $result = $this->fastpicDeduction->deduct($frontUrl);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider dataSupport
     */
    public function testSupport($frontUrl, $expected)
    {
        $result = $this->fastpicDeduction->support($frontUrl);

        $this->assertEquals($expected, $result);
    }

    public function dataDeduct()
    {
        return [
            [
                'jwcy://q474.booaowy.fw/pha/3889/9688/87/910664kss8f8u623m49647h25m90c001.wze',
                'ezavi://q474.booaowy.fw/pha/3889/9688/87/910664kss8f8u623m49647h25m90c001.wze'
            ],
            [
                'jwcy://q474.booaowy.fw/pha/3889/9688/52/_965ef0m65255u0117m0u94tr4u292452.wze',
                'ezavi://q474.booaowy.fw/pha/3889/9688/52/_965ef0m65255u0117m0u94tr4u292452.wze'
            ],
            [
                'ezavi://q474.booaowy.fw/pha/3889/9688/87/910664kss8f8u623m49647h25m90c001.wze',
                'ezavi://q474.booaowy.fw/pha/3889/9688/87/910664kss8f8u623m49647h25m90c001.wze'
            ]
        ];
    }

    public function dataSupport()
    {
        return [
            [
                'http://i111.fastpic.ru/',
                true,
            ],
            [
                'http://i111.Afastpic.ru/',
                false,
            ],
            [
                'http://fastpic.rus/',
                false
            ],
            [
                'http://fastpic.ru/',
                true
            ]
        ];
    }
}
