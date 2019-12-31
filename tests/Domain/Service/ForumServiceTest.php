<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Factory\ForumFactory;
use OnlyTracker\Domain\Identity\ForumId;
use OnlyTracker\Domain\Repository\ForumRepositoryInterface;
use OnlyTracker\Domain\Service\ForumService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use PHPUnit\Framework\TestCase;

class ForumServiceTest extends TestCase
{
    /** @var \OnlyTracker\Domain\Repository\ForumRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $repo;
    /** @var \OnlyTracker\Domain\Factory\ForumFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Service\ForumService */
    private $service;

    protected function setUp()
    {
        $this->repo     = $this->createMock(ForumRepositoryInterface::class);
        $this->factory  = new ForumFactory(new DateTimeUtil);
        $this->service  = new ForumService($this->repo, $this->factory);
    }

    /**
     * @dataProvider dataGetCase
     */
    public function testGetOrMakeGetCase($id, $title)
    {
        // prepare
        $preparedForum = $this->factory->make($id, $title);
        $this->repo->method('findBy')->willReturn([$preparedForum]);

        // do
        $forum = $this->service->getOrMake($id, $title);

        // assert
        $this->assertSame($preparedForum, $forum);
    }

    /**
     * @dataProvider dataMakeCase
     */
    public function testGetOrMakeMakeCase($id, $title)
    {
        // prepare
        $this->repo->method('findBy')->willReturn([]);

        // do
        $forum = $this->service->getOrMake($id, $title);

        // assert
        $this->assertEquals($id->value(), $forum->getId()->value());
        $this->assertEquals($title, $forum->getTitle());
    }

    public function dataGetCase()
    {
        return [
            [
                new ForumId(5), '5 title',
            ],
            [
                new ForumId(7), '7 title',
            ],
        ];
    }

    public function dataMakeCase()
    {
        return [
            [
                new ForumId(5), '5 title',
            ],
            [
                new ForumId(7), '7 title',
            ],
        ];
    }
}
