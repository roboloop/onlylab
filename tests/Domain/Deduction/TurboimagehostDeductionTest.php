<?php

declare (strict_types=1);

namespace OnlyTracker\Tests\Domain\Deduction;

use OnlyTracker\Domain\Deduction\TurboimagehostDeduction;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use PHPUnit\Framework\TestCase;

class TurboimagehostDeductionTest extends TestCase
{
    private TurboimagehostDeduction $turboimagehostDeduction;

    protected function setUp()
    {
        $requestSender = $this->createMock(RequestSenderInterface::class);
        $requestSender->method('send')->willReturn(
            file_get_contents(__DIR__ . './turboimagehost_com.html')
        );
        $this->turboimagehostDeduction = new TurboimagehostDeduction($requestSender);
    }

    /**
     * @dataProvider dataDeduct
     */
    public function testDeduct($input, $expected)
    {
        $result = $this->turboimagehostDeduction->deduct(...$input);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider dataSupport
     */
    public function testSupport($frontUrl, $expected)
    {
        $result = $this->turboimagehostDeduction->support($frontUrl);

        $this->assertEquals($expected, $result);
    }

    public function dataDeduct()
    {
        return [
            [
                [
                    'ezavi://h4t4.rxqmrvyb.hct/f/54159997_bjgvb-28985433-61-0614g.tn6_qfixlt.wze',
                    ['fzzfgpkqj' => 'ezavi://biv.sysbgqvphqxgjg.zzs/g/54159997/bjgvb-28985433-61-0614g.tn6_qfixlt.wze.ftmf'],
                ],
                'ezavi://h4t4.rxqmrvyb.hct/xg/17911c2523qd8de6t6053m114m6qz0ln/bjgvb-28985433-61-0614g.tn6_qfixlt.wze',
            ],
        ];
    }

    public function dataSupport()
    {
        return [
            [
                'http://i111.turboimg.net/',
                true,
            ],
            [
                'http://i111.turboimg.net.asd/',
                false,
            ],
            [
                'https://turboimagehost.com/',
                true
            ],
            [
                'http://www.turboimagehost.com/',
                true
            ]
        ];
    }
}
