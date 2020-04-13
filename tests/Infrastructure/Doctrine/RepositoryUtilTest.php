<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class RepositoryUtilTest extends TestCase
{
    /** @var \Doctrine\ORM\EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $entityManager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    /**
     * @dataProvider asSubData
     */
    public function testAsSub($source)
    {

    }

    public function asSubData()
    {
        return [
            [
                (new QueryBuilder($this->entityManager))
                    ->select('q', 'w')
                    ->from('Entity\Fake', 'q')
                    ->leftJoin()
            ]
        ];
    }
}
