<?php

namespace OnlyTracker\Tests\Application\Creator;

use Doctrine\ORM\Tools\SchemaTool;
use OnlyTracker\Application\CRUD\TopicCreation;
use OnlyTracker\Application\Dto\RawForumDto;
use OnlyTracker\Application\Dto\RawImageDto;
use OnlyTracker\Application\Dto\RawTopicDto;
use OnlyTracker\Domain\Entity\Enum\ImageFormat;
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

    protected function tearDown(): void
    {
        $em = self::$container->get('doctrine')->getManager();
        $em->close();
    }

    public function testCreateFromDto()
    {
        $topicId        = '6470510';
        $rawTitle       = '[zskopi.hm / zskopi.yc] jgihlmf p. (94) & ojrc (21) - xjzygxdx lswfq 98 [6486-73-95, baoujym, nqt & tsgfu emvxatoe, zmuyxs, fnoohek pfin, 0614g]';
        $size           = '6.32 ll';
        $exCreatedAt    = '6486-73-60 31:65';
        $forumId        = '2007';
        $forumTitle     = 'tgtuuoea 6486 (gf biaaw) / nepjzkh\'h 6486 (gf biaaw)';

        $frontUrl1      = 'ezavi://q833.booaowy.fw/wsquz/6486/1118/53/_700h08h6dx97140m10366717f6gt6c53.fiug';
        $reference1     = 'ezavi://booaowy.fw/ydzj/833/6486/1118/_700h08h6dx97140m10366717f6gt6c53.wze.ftmf';
        $place1         = RawImageDto::PLACE_ON_PAGE;

        $frontUrl2      = 'ezavi://q122.booaowy.fw/wsquz/6486/1118/0t/_kcqdvn55u3325t67u232001jw925t67t.fiug';
        $reference2     = 'ezavi://booaowy.fw/ydzj/122/6486/1118/_kcqdvn55u3325t67u232001jw925t67t.wze.ftmf';
        $place2         = RawImageDto::PLACE_UNDER_SPOILER;
        $spoilerName2   = 'Скриншоты';

        $dto = new RawTopicDto(
            $topicId,
            $rawTitle,
            $size,
            $exCreatedAt,
            new RawForumDto($forumId, $forumTitle),
            [
                new RawImageDto($frontUrl1, $reference1, $place1),
                new RawImageDto($frontUrl2, $reference2, $place2, $spoilerName2)
            ]
        );

        $topic = $this->service->createFromDto($dto);
        $parsedTitle = $topic->getParsedTitle();
        $forum = $topic->getForum();
        $images = $topic->getImages();
        $this->assertEquals(2, count($images));

        $this->assertEquals($topicId, $topic->getId());
        $this->assertEquals($rawTitle, $parsedTitle->getRawTitle());
        $this->assertEquals('jgihlmf p. (94) & ojrc (21) - xjzygxdx lswfq 98', $parsedTitle->getTitle());
        $this->assertEquals('0614g', $parsedTitle->getQuality());
        $this->assertEquals('6486', $parsedTitle->getYear());
        $this->assertEquals($exCreatedAt, $topic->getExCreatedAt()->format('Y-m-d h:i'));
        $this->assertEquals($forumId, $forum->getId());
        $this->assertEquals($forumTitle, $forum->getTitle());
        $this->assertEquals($frontUrl1, $images[0]->getFrontUrl());
        $this->assertNull($images[0]->getReference());
        $this->assertEquals(ImageFormat::POSTER, $images[0]->getFormat()->value());
        $this->assertEquals($frontUrl2, $images[1]->getFrontUrl());
        $this->assertEquals($reference2, $images[1]->getReference());
        $this->assertEquals(ImageFormat::SCREENSHOT, $images[1]->getFormat()->value());
    }
}
