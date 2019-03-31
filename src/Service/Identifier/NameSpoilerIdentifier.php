<?php

namespace App\Service\Identifier;

use App\Constant\ImageType;

class NameSpoilerIdentifier
{
    private $screenshotNames = [
        'Скриншот',
        'Скриншоты',
        'Screenshots',
        'Примеры',
    ];

    private $screenListingNames = [
        'Скринлист',
        'ScreenListing',
    ];

    private $gifNames = [
        'GIF',
    ];

    public function isScreenshots(string $header)
    {
        return $this->search($header, $this->screenshotNames);
    }

    public function isScreenListing(string $header)
    {
        return $this->search($header, $this->screenListingNames);
    }

    public function isGif(string $header)
    {
        return $this->search($header, $this->gifNames);
    }

    private function search(string $header, array $names)
    {
        foreach ($names as $name) {
            if (false !== mb_eregi($name, $header)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Identify type of image relying of spoiler header
     *
     * @param string $header
     *
     * @return int
     */
    public function identifyType(string $header)
    {
        if ($this->isScreenshots($header)) {
            $type = ImageType::SCREENSHOT;
        } elseif ($this->isScreenListing($header)) {
            $type = ImageType::SCREENLISTING;
        } elseif ($this->isGif($header)) {
            $type = ImageType::GIF;
        } else {
            $type = ImageType::OTHER;
        }

        return $type;
    }
}
