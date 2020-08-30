<?php

declare (strict_types=1);

namespace OnlyTracker\Tests\Domain\Deduction;

use OnlyTracker\Domain\Deduction\ImgboxDeduction;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ImgboxDeductionTest extends TestCase
{
    private ImgboxDeduction $imgboxDeduction;

    protected function setUp()
    {
        $this->imgboxDeduction = new ImgboxDeduction();
    }

    /**
     * @dataProvider dataDeduct
     */
    public function testDeduct($frontUrl, $expected)
    {
        $result = $this->imgboxDeduction->deduct($frontUrl);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider dataSupport
     */
    public function testSupport($frontUrl, $expected)
    {
        $result = $this->imgboxDeduction->support($frontUrl);

        $this->assertEquals($expected, $result);
    }

    public function dataDeduct()
    {
        return [
            [
                'ezavi://jpikex6.wrqovk.zzs/09/64/ipaqjjaz_z.wze',
                'ezavi://jpikex6.wrqovk.zzs/09/64/ipaqjjaz_z.wze',
            ],
            [
                'ezavi://qfixlt6.wrqovk.zzs/54/t4/ll6wmgo8_f.wze',
                'ezavi://jpikex6.wrqovk.zzs/54/t4/ll6wmgo8_z.wze',
            ],
            [
                'ezavi://qfixlt6.wrqovk.zzs/h6/55/0n6dhe0f_f.wze',
                'ezavi://jpikex6.wrqovk.zzs/h6/55/0n6dhe0f_z.wze',
            ],
        ];
    }

    public function dataSupport()
    {
        return [
            [
                'https://thumbs2.imgbox.com/h6/55/0n6dhe0f_f.jpg',
                true,
            ],
            [
                'https://thumbs2.imgbox.com/',
                true,
            ],
            [
                'https://imgbox.com/',
                true,
            ],
            [
                'https://thumbs2.imgbox.coms/',
                false,
            ],
            [
                'https://simgbox.com/',
                false,
            ],
        ];
    }
}
