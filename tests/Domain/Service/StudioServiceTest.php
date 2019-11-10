<?php

namespace App\Tests\Domain\Service;

use App\Domain\Entity\Enum\StudioStatus;
use App\Domain\Entity\Studio;
use App\Domain\Factory\StudioFactory;
use App\Domain\Service\StudioService;
use App\Infrastructure\Util\DateTimeUtil;
use App\Tests\Helpers\AssertArrayTrait;
use App\Tests\Stubs\Infrastructure\Repository\ArrayStudioRepository;
use PHPUnit\Framework\TestCase;

class StudioServiceTest extends TestCase
{
    use AssertArrayTrait;

    /** @var \App\Tests\Stubs\Infrastructure\Repository\ArrayStudioRepository */
    private $repo;
    /** @var \App\Domain\Factory\StudioFactory */
    private $factory;
    /** @var \App\Domain\Service\StudioService */
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
