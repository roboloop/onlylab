<?php

namespace App\Tests\Domain\Service;

use App\Domain\Deduction\FastpicDeduction;
use App\Domain\Deduction\OriginalUrlDeduction;
use App\Domain\Deduction\PicshickDeduction;
use App\Domain\Entity\Enum\ImageFormat;
use App\Domain\Factory\ImageFactory;
use App\Domain\Service\ImageService;
use App\Infrastructure\Util\DateTimeUtil;
use App\Tests\Stubs\Infrastructure\Fixture\FixtureLoader;
use App\Tests\Stubs\Infrastructure\Repository\ArrayImageRepository;
use PHPUnit\Framework\TestCase;

class ImageServiceTest extends TestCase
{
    /** @var \App\Tests\Stubs\Infrastructure\Repository\ArrayImageRepository */
    private $repository;
    /** @var \App\Domain\Factory\ImageFactory */
    private $factory;
    /** @var \App\Domain\Deduction\OriginalUrlDeduction */
    private $urlDeduction;
    /** @var \App\Domain\Service\ImageService */
    private $service;

    protected function setUp()
    {
        $this->repository   = new ArrayImageRepository;
        $this->factory      = new ImageFactory($this->repository, new DateTimeUtil);
        $this->urlDeduction = new OriginalUrlDeduction([
            new FastpicDeduction,
            new PicshickDeduction,
        ]);
        $this->service      = new ImageService($this->factory, $this->repository, $this->urlDeduction);
    }

    /**
     * @dataProvider makePosterData
     */
    public function testMakePosterImage($frontUrl)
    {
        $topic = FixtureLoader::topic();
        $image = $this->service->makePosterImage($topic, $frontUrl);

        $this->assertEquals($frontUrl, $image->getFrontUrl());
        $this->assertNull($image->getReference());
        $this->assertNull($image->getOriginal());
    }

    /**
     * @dataProvider makeUnderSpoilerData
     */
    public function testMakeUnderSpoilerImage($frontUrl, $reference, $spoilerName, $originalExpects, $formatExpects)
    {
        $topic = FixtureLoader::topic();

        $image = $this->service->makeUnderSpoilerImage($topic, $frontUrl, $reference, $spoilerName);

        $this->assertEquals($frontUrl, $image->getFrontUrl());
        $this->assertEquals($reference, $image->getReference());
        $this->assertEquals($originalExpects, $image->getOriginal());
        $this->assertEquals($formatExpects, $image->getFormat()->value());
    }

    public function makePosterData()
    {
        return [
            [
                'ezavi://q833.booaowy.fw/wsquz/6486/1118/53/_98c8u093u183ww962tp3m1ad6t6ef260.fiug',
            ],
            [
                'jwcy://lwzijidmzpe.fw/kwliunqs/6/406739-wsquz.fiug',
            ],
        ];
    }

    public function makeUnderSpoilerData()
    {
        return [
            [
                'ezavi://q772.booaowy.fw/wsquz/6486/7254/1u/_f8f89329709cb14m2c3h0m009043lw1u.fiug',
                'ezavi://booaowy.fw/ydzj/772/6486/7254/_f8f89329709cb14m2c3h0m009043lw1u.wze.ftmf',
                'Скриншот',
                'ezavi://q772.booaowy.fw/pha/6486/7254/1u/_f8f89329709cb14m2c3h0m009043lw1u.wze?scks=2',
                ImageFormat::SCREENSHOT,
            ],
        ];
    }
}
