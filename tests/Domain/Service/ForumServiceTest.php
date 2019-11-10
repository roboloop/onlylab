<?php

namespace App\Tests\Domain\Service;

use App\Domain\Factory\ForumFactory;
use App\Domain\Service\ForumService;
use App\Infrastructure\Util\DateTimeUtil;
use App\Tests\Stubs\Infrastructure\Repository\ArrayForumRepository;
use PHPUnit\Framework\TestCase;

class ForumServiceTest extends TestCase
{
    /** @var \App\Tests\Stubs\Infrastructure\Repository\ArrayForumRepository */
    private $repo;
    /** @var \App\Domain\Factory\ForumFactory */
    private $factory;
    /** @var \App\Domain\Service\ForumService */
    private $service;

    protected function setUp()
    {
        $this->repo     = new ArrayForumRepository;
        $this->factory  = new ForumFactory($this->repo, new DateTimeUtil);
        $this->service  = new ForumService($this->repo, $this->factory);
    }

    public function testGetOrMake()
    {
        // TODO:
    }
}
