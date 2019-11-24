<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Factory\GenreFactory;
use OnlyTracker\Domain\Service\GenreService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use OnlyTracker\Tests\Helpers\AssertArrayTrait;
use OnlyTracker\Tests\Stubs\Infrastructure\Repository\ArrayGenreRepository;
use PHPUnit\Framework\TestCase;

class GenreServiceTest extends TestCase
{
    use AssertArrayTrait;

    /** @var \OnlyTracker\Tests\Stubs\Infrastructure\Repository\ArrayGenreRepository */
    private $repo;
    /** @var \OnlyTracker\Domain\Factory\GenreFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Service\GenreService */
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

    public function testApprove()
    {
        $this->repo->save($genre = $this->factory->make('Dummy title', null, false));

        $this->service->approve($genre);

        $genres = $this->repo->findAll();
        $this->assertEquals(1, count($genres));
        $this->assertTrue(reset($genres)->isApproved());
    }

    public function testDisapprove()
    {
        $this->repo->save($genre = $this->factory->make('Dummy title', null, true));

        $this->service->disapprove($genre);

        $genres = $this->repo->findAll();
        $this->assertEquals(1, count($genres));
        $this->assertFalse(reset($genres)->isApproved());
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
