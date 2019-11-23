<?php

namespace OnlyTracker\Tests\Domain\Entity\Enum;

use OnlyTracker\Domain\Entity\Enum\ImageFormat;
use PHPUnit\Framework\TestCase;

class ImageFormatTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testCreateFromSpoilerName($header, $expectedStatus)
    {
        $imageFormat = ImageFormat::createFromSpoilerName($header);

        $this->assertEquals($expectedStatus, $imageFormat->value());
    }

    public function data()
    {
        return [
            [ 'СкРиНшОт',       ImageFormat::SCREENSHOT ],
            [ 'СкриншотЫ',      ImageFormat::SCREENSHOT ],
            [ 'Screenshots',    ImageFormat::SCREENSHOT ],
            [ 'Примеры',        ImageFormat::SCREENSHOT ],
            [ 'Скринлист',      ImageFormat::SCREENLISTING ],
            [ 'ScreenListing',  ImageFormat::SCREENLISTING ],
            [ 'ScreenListings', ImageFormat::SCREENLISTING ],
            [ 'GIF',            ImageFormat::GIF ],
            [ '  GIFs  ',       ImageFormat::GIF ],
            [ ' Скринлис т  ',  ImageFormat::OTHER ],
        ];
    }
}
