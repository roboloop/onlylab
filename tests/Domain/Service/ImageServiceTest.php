<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Deduction\FastpicDeduction;
use OnlyTracker\Domain\Deduction\OriginalUrlDeduction;
use OnlyTracker\Domain\Deduction\PicshickDeduction;
use OnlyTracker\Domain\Entity\Enum\ImageFormat;
use OnlyTracker\Domain\Factory\ImageFactory;
use OnlyTracker\Domain\Repository\ImageRepositoryInterface;
use OnlyTracker\Domain\Service\ImageService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use OnlyTracker\Tests\Stubs\Infrastructure\Fixture\FixtureLoader;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ImageServiceTest extends TestCase
{
    /** @var \OnlyTracker\Domain\Repository\ImageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $repository;
    /** @var \OnlyTracker\Domain\Factory\ImageFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Deduction\OriginalUrlDeduction */
    private $urlDeduction;
    /** @var \OnlyTracker\Domain\Service\ImageService */
    private $service;

    protected function setUp()
    {
        $this->repository   = $this->createMock(ImageRepositoryInterface::class);
        $this->factory      = new ImageFactory($this->repository, new DateTimeUtil);
        $this->urlDeduction = new OriginalUrlDeduction([
            new FastpicDeduction,
            new PicshickDeduction,
        ]);
        $this->service      = new ImageService($this->repository, $this->factory, $this->urlDeduction);
    }

    /**
     * @dataProvider makePosterData
     */
    public function testMakePosterImage($frontUrl)
    {
        // Prepare
        $topic = FixtureLoader::topic();
        $this->repository->method('nextIdentity')->willReturn(Uuid::uuid4()->toString());

        // Do
        $image = $this->service->makePosterImage($topic, $frontUrl);

        // Assert
        $this->assertEquals($frontUrl, $image->getFrontUrl());
        $this->assertNull($image->getReference());
        $this->assertNull($image->getOriginal());
    }

    /**
     * @dataProvider makeUnderSpoilerData
     */
    public function testMakeUnderSpoilerImage($frontUrl, $reference, $spoilerName, $originalExpects, $formatExpects)
    {
        // Prepare
        $topic = FixtureLoader::topic();
        $this->repository->method('nextIdentity')->willReturn(Uuid::uuid4()->toString());

        // Do
        $image = $this->service->makeUnderSpoilerImage($topic, $frontUrl, $reference, $spoilerName);

        // Assert
        $this->assertEquals($frontUrl, $image->getFrontUrl());
        $this->assertEquals($reference, $image->getReference());
        $this->assertEquals($originalExpects, $image->getOriginal());
        $this->assertEquals($formatExpects, $image->getFormat()->value());
    }

    public function testMarkAsBanner()
    {
        // TODO:
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
