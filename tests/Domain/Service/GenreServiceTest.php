<?php

namespace App\Tests\Domain\Service;

use App\Domain\Entity\Genre;
use App\Domain\Factory\GenreFactory;
use App\Domain\Service\GenreService;
use App\Infrastructure\Util\DateTimeUtil;
use App\Tests\Helpers\AssertArrayTrait;
use App\Tests\Stubs\Infrastructure\Repository\ArrayGenreRepository;
use PHPUnit\Framework\TestCase;

class GenreServiceTest extends TestCase
{
    use AssertArrayTrait;

    /** @var \App\Tests\Stubs\Infrastructure\Repository\ArrayGenreRepository */
    private $repo;
    /** @var \App\Domain\Factory\GenreFactory */
    private $factory;
    /** @var \App\Domain\Service\GenreService */
    private $service;

    protected function setUp()
    {
        $this->repo     = new ArrayGenreRepository;
        $this->factory  = new GenreFactory($this->repo, new DateTimeUtil);
        $this->service  = new GenreService($this->repo, $this->factory);
    }

    /**
     * @dataProvider data
     */
    public function testGetOrMakeOrBoth($repoData, $titles, $expectedRepoData)
    {
        // Prepare
        foreach ($repoData as $datum) {
            $this->repo->save($this->factory->make($datum, null, false));
        }

        // Do
        $result = $this->service->getOrMakeOrBoth($titles);

        // Assert
        $titleCallback = function (Genre $genre) {
            return $genre->getTitle();
        };

        $repo        = array_map($titleCallback, $this->repo->findAll());
        $rawReturned = array_map($titleCallback, $result);

        $this->assertEquals(count($titles), count($result));
        $this->assertEquals(count($expectedRepoData), count($repo));
        $this->assertArrayPopulation($expectedRepoData, $repo);
        $this->assertArrayPopulation($titles, $rawReturned);
    }

    public function data()
    {
        return [
            [
                ['diff', 'same', 'module'],
                ['ddd', 'diff', 'module'],
                ['diff', 'same', 'module', 'ddd'],
            ],
        ];
    }
}
