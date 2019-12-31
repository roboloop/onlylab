<?php

namespace OnlyTracker\Tests\Application\Creator;

use Doctrine\ORM\Tools\SchemaTool;
use OnlyTracker\Application\CRUD\TopicCreation;
use OnlyTracker\Application\Dto\RawForumDto;
use OnlyTracker\Application\Dto\RawImageDto;
use OnlyTracker\Application\Dto\RawTopicDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TopicCreatorTest extends KernelTestCase
{
    /** @var \OnlyTracker\Application\CRUD\TopicCreation */
    private $service;

    protected function setUp()
    {
        static::bootKernel();
        $em = self::$container->get('doctrine')->getManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        $schemaTool->updateSchema($metadata);

        $this->service = static::$container->get(TopicCreation::class);
    }

    protected function tearDown()
    {
        $em = self::$container->get('doctrine')->getManager();
        $em->close();
    }

    public function testCreateFromDto()
    {
        $dto = new RawTopicDto(
            '6470510',
            '[zskopi.hm / zskopi.yc] jgihlmf p. (94) & ojrc (21) - xjzygxdx lswfq 98 [6486-73-95, baoujym, nqt & tsgfu emvxatoe, zmuyxs, fnoohek pfin, 0614g]',
            '6.32 ll',
            '6486-73-60 31:65',
            new RawForumDto(
                '2007',
                'tgtuuoea 6486 (gf biaaw) / nepjzkh\'h 6486 (gf biaaw)'
            ),
            [
                new RawImageDto(
                    'ezavi://q833.booaowy.fw/wsquz/6486/1118/53/_700h08h6dx97140m10366717f6gt6c53.fiug',
                    'ezavi://booaowy.fw/ydzj/833/6486/1118/_700h08h6dx97140m10366717f6gt6c53.wze.ftmf',
                    RawImageDto::PLACE_ON_PAGE
                ),
                new RawImageDto(
                    'ezavi://q122.booaowy.fw/wsquz/6486/1118/0t/_kcqdvn55u3325t67u232001jw925t67t.fiug',
                    'ezavi://booaowy.fw/ydzj/122/6486/1118/_kcqdvn55u3325t67u232001jw925t67t.wze.ftmf',
                    RawImageDto::PLACE_UNDER_SPOILER,
                    'Скриншоты'
                ),
            ]
        );

        $topic = $this->service->createFromDto($dto);
    }
}
