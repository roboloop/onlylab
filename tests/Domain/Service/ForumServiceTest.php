<?php

namespace OnlyTracker\Tests\Domain\Service;

use OnlyTracker\Domain\Factory\ForumFactory;
use OnlyTracker\Domain\Service\ForumService;
use OnlyTracker\Infrastructure\Util\DateTimeUtil;
use OnlyTracker\Tests\Stubs\Infrastructure\Repository\ArrayForumRepository;
use PHPUnit\Framework\TestCase;

class ForumServiceTest extends TestCase
{
    /** @var \OnlyTracker\Tests\Stubs\Infrastructure\Repository\ArrayForumRepository */
    private $repo;
    /** @var \OnlyTracker\Domain\Factory\ForumFactory */
    private $factory;
    /** @var \OnlyTracker\Domain\Service\ForumService */
    private $service;

    protected function setUp()
    {
        $this->repo     = new ArrayForumRepository;
        $this->factory  = new ForumFactory($this->repo, new DateTimeUtil);
        $this->service  = new ForumService($this->repo, $this->factory);
    }

    /**
     * @dataProvider dataGetCase
     */
    public function testGetOrMakeGetCase($repoData, $forumData)
    {
        // prepare
        foreach ($repoData as $datum) {
            $this->repo->save($this->factory->make(...$datum));
        }
        // do
        $forum = $this->service->getOrMake(...$forumData);

        // assert
        $repo = $this->repo->findAll();
        $this->assertEquals(count($repoData), count($repo));
        $this->assertEquals($forum->getTitle(), $forumData[1]);
    }

    /**
     * @dataProvider dataMakeCase
     */
    public function testGetOrMakeMakeCase($repoData, $forumData)
    {
        // prepare
        foreach ($repoData as $datum) {
            $this->repo->save($this->factory->make(...$datum));
        }
        // do
        $forum = $this->service->getOrMake(...$forumData);

        // assert
        $repo = $this->repo->findAll();
        $this->assertEquals(count($repoData) + 1, count($repo));
        $this->assertEquals($forum->getTitle(), $forumData[1]);
    }

    public function dataGetCase()
    {
        return [
            [
                [
                    [5, '5 title'],
                    [7, '7 title'],
                ],
                [5, '5 title'],
            ],
            [
                [
                    [5, '5 title'],
                    [5, '6 title'],
                    [7, '7 title'],
                ],
                [5, '6 title'],
            ]
        ];
    }

    public function dataMakeCase()
    {
        return [
            [
                [
                    [5, '5 title'],
                    [7, '7 title'],
                ],
                [6, '6 title'],
            ]
        ];
    }
}
