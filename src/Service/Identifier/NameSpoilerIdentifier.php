<?php

namespace App\Service\Identifier;

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
}
