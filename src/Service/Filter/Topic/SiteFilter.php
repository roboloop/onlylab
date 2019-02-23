<?php

namespace App\Filter\Topic;

class SiteFilter
{
    private $banned = [
        'brazzers.com',
        'BlackIsBetter.com',
    ];

    private $bannedString;

    private $bannedPattern;

    public function __construct()
    {
        $this->bannedString = implode(' ', $this->banned);

        foreach ($this->banned as $ban) {
            $this->bannedPattern[] = "~$ban~i";
        }
    }

    public function filter(string $site): bool
    {
        preg_replace($this->bannedPattern, '', $site, -1, $res);

        return $res >= 1;
    }
}
