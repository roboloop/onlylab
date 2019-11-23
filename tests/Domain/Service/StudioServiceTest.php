<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Entity\Enum\StudioStatus;
use OnlyTracker\Domain\Entity\Studio;
use OnlyTracker\Domain\Factory\StudioFactory;
use OnlyTracker\Domain\Service\StudioService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use OnlyTracker\Tests\Helpers\AssertArrayTrait;
use OnlyTracker\Tests\Stubs\Infrastructure\Repository\ArrayStudioRepository;
use PHPUnit\Framework\TestCase;

class StudioServiceTest extends TestCase
{
    use AssertArrayTrait;

    /** @var \OnlyTracker\Tests\Stubs\Infrastructure\Repository\ArrayStudioRepository */
    private $repo;
    /** @var \OnlyTracker\Domain\Factory\StudioFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Service\StudioService */
    private $service;

    protected function setUp()
    {
        $this->repo     = new ArrayStudioRepository;
        $this->factory  = new StudioFactory($this->repo, new DateTimeUtil);
        $this->service  = new StudioService($this->repo, $this->factory);
    }

    /**
     * @dataProvider data
     */
    public function testGetOrMakeOrBoth($repoData, $urls, $expectedRepoData)
    {
        // Prepare
        foreach ($repoData as $datum) {
            $this->repo->save($this->factory->make($datum, new StudioStatus(StudioStatus::TYPICAL)));
        }

        // Do
        $result = $this->service->getOrMakeOrBoth($urls);

        // Assert
        $urlCallback = function (Studio $genre) {
            return $genre->getUrl();
        };

        $repo        = array_map($urlCallback, $this->repo->findAll());
        $rawReturned = array_map($urlCallback, $result);

        $this->assertEquals(count($urls), count($result));
        $this->assertEquals(count($expectedRepoData), count($repo));
        $this->assertArrayPopulation($expectedRepoData, $repo);
        $this->assertArrayPopulation($urls, $rawReturned);
    }

    public function data()
    {
        return [
            [
                ['https://translate.google.com/', 'https://translate.yandex.ru/', 'https://translate.bing.com/'],
                ['https://translate.google.com/', 'https://translate.ru/', 'https://translate.yandex.ru/'],
                ['https://translate.google.com/', 'https://translate.ru/', 'https://translate.yandex.ru/', 'https://translate.bing.com/'],
            ],
        ];
    }
}
