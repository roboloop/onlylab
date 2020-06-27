<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Factory\StudioFactory;
use OnlyTracker\Domain\Repository\StudioRepositoryInterface;
use OnlyTracker\Domain\Service\StudioService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use OnlyTracker\Tests\Helpers\AssertArrayTrait;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class StudioServiceTest extends TestCase
{
    use AssertArrayTrait;

    /** @var \OnlyTracker\Domain\Repository\StudioRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $repo;
    /** @var \OnlyTracker\Domain\Factory\StudioFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Service\StudioService */
    private $service;

    protected function setUp()
    {
        $this->repo     = $this->createMock(StudioRepositoryInterface::class);
        $this->factory  = new StudioFactory($this->repo, new DateTimeUtil);
        $this->service  = new StudioService($this->repo, $this->factory);
    }

    /**
     * @dataProvider data
     */
    public function testGetOrMakeOrBoth($findByData, $rawUrls)
    {
        // Prepare
        $this->repo->method('nextIdentity')->willReturnOnConsecutiveCalls(...$this->generateIds(10));
        foreach ($findByData as $datum) {
            $findBy[] = $this->factory->make($datum, StudioStatus::typical());
        }
        $this->repo->method('findBy')->willReturn($findBy);

        // Do
        $studios = $this->service->getOrMakeOrBoth($rawUrls);

        // Assert
        $urlCallback = function (Studio $genre) {
            return $genre->getUrl();
        };

        $compare = function (string $a, string $b) {
            return mb_strtolower($a) <=> mb_strtolower($b);
        };

        $studioUrls = array_map($urlCallback, $studios);
        $this->assertArrayPopulation($rawUrls, $studioUrls, $compare);
        $this->assertArrayPopulation($findByData, $studioUrls, $compare);
    }

    public function data()
    {
        return [
            [
                ['https://translate.google.com/', 'https://translate.ru/'],
                ['https://translate.google.com/', 'https://translate.ru/', 'https://TRANSLATE.ru/', 'https://translate.yandex.ru/'],
            ],
        ];
    }

    private function generateIds($amount)
    {
        for ($i = 0; $i < $amount; $i++) {
            $ids[] = Uuid::uuid4()->toString();
        }

        return $ids ?? [];
    }

    private function makeStudio(string $url, ?StudioStatus $status = null): Studio
    {
        if (null === $status) {
            $status = StudioStatus::typical();
        }

        return new Studio(
            Uuid::uuid4()->toString(),
            $url,
            $status,
            new \DateTimeImmutable(),
        );
    }
}
