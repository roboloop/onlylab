<?php

namespace App\Tests\Infrastructure\Util\Parser\Title;

use App\Infrastructure\Util\Parser\Title\GenreParser;
use App\Infrastructure\Util\Parser\Title\OriginalTitleParser;
use App\Infrastructure\Util\Parser\Title\QualityParser;
use App\Infrastructure\Util\Parser\Title\StudioParser;
use App\Infrastructure\Util\Parser\Title\TitleParserManager;
use App\Infrastructure\Util\Parser\Title\YearParser;
use PHPUnit\Framework\TestCase;

class TitleParserManagerTest extends TestCase
{
    /** @var \App\Infrastructure\Util\Parser\Title\TitleParserManager */
    private $manager;

    protected function setUp()
    {
        $this->manager = new TitleParserManager(
            new GenreParser,
            new OriginalTitleParser,
            new QualityParser,
            new StudioParser,
            new YearParser
        );
    }

    /**
     * @dataProvider data
     */
    public function testGenres($source, $expected)
    {
        $result = $this->manager->genres($source);

        $this->assertEquals($expected['genres'], $result);
    }

    /**
     * @dataProvider data
     */
    public function testOriginalTitle($source, $expected)
    {
        $result = $this->manager->originalTitle($source);

        $this->assertEquals($expected['originalTitle'], $result);
    }

    /**
     * @dataProvider data
     */
    public function testQuality($source, $expected)
    {
        $result = $this->manager->quality($source);

        $this->assertEquals($expected['quality'], $result);
    }

    /**
     * @dataProvider data
     */
    public function testStudios($source, $expected)
    {
        $result = $this->manager->studios($source);

        $this->assertEquals($expected['studios'], $result);
    }

    /**
     * @dataProvider data
     */
    public function testYear($source, $expected)
    {
        $result = $this->manager->year($source);

        $this->assertEquals($expected['year'], $result);
    }

    public function data()
    {
        return require 'input.php';
    }
}
