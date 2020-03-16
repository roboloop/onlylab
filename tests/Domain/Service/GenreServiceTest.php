<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Factory\GenreFactory;
use OnlyTracker\Domain\Repository\GenreRepositoryInterface;
use OnlyTracker\Domain\Service\GenreService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use OnlyTracker\Tests\Helpers\AssertArrayTrait;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class GenreServiceTest extends TestCase
{
    use AssertArrayTrait;

    /** @var \OnlyTracker\Domain\Repository\GenreRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $repo;
    /** @var \OnlyTracker\Domain\Factory\GenreFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Service\GenreService */
    private $service;

    protected function setUp()
    {
        $this->repo     = $this->createMock(GenreRepositoryInterface::class);
        $this->factory  = new GenreFactory($this->repo, new DateTimeUtil);
        $this->service  = new GenreService($this->repo, $this->factory);
    }

    /**
     * @dataProvider data
     */
    public function testGetOrMakeOrBoth($findByData, $rawTitles)
    {
        // Prepare
        $this->repo->method('nextIdentity')->willReturnOnConsecutiveCalls(...$this->generateIds(10));
        foreach ($findByData as $datum) {
            $findBy[] = $this->factory->make($datum, null, false);
        }
        $this->repo->method('findBy')->willReturn($findBy);

        // Do
        $genres = $this->service->getOrMakeOrBoth($rawTitles);

        // Assert
        $titleCallback = fn(Genre $genre) => $genre->getTitle();

        $compare = fn($a, $b) => mb_strtolower($a) <=> mb_strtolower($b);

        $genreTitles = array_map($titleCallback, $genres);
        $this->assertArrayPopulation($rawTitles, $genreTitles, $compare);
        $this->assertArrayPopulation($findByData, $genreTitles, $compare);
    }

    public function testApprove()
    {
        // Prepare
        $this->repo->method('nextIdentity')->willReturnOnConsecutiveCalls(...$this->generateIds(10));
        $genre = $this->factory->make('Dummy title', null, false);

        // Do
        $this->service->approve($genre);

        // Assert
        $this->assertTrue($genre->isApproved());
    }

    public function testDisapprove()
    {
        // Prepare
        $this->repo->method('nextIdentity')->willReturnOnConsecutiveCalls(...$this->generateIds(10));
        $genre = $this->factory->make('Dummy title', null, true);

        // Do
        $this->service->disapprove($genre);

        // Assert
        $this->assertFalse($genre->isApproved());
    }

    public function data()
    {
        return [
            [
                ['diff', 'module'],
                ['ddd', 'DIFF', 'DiFf', 'module'],
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
}
