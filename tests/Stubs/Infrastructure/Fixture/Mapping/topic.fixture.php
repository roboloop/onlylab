<?php

return [
    \OnlyTracker\Domain\Entity\Embeddable\ParsedTitle::class => [
        'parsedTitle0' => [
            '__construct' => [
                'Hello Kitty 2005 1080p',
                'Hello Kitty',
                '2005',
                '1080p',
            ],
        ],
    ],

    \OnlyTracker\Domain\Entity\Topic::class => [
        'topic0' => [
            '__construct' => [
                '<uuid()>',
                '<numberBetween(1, 100)>',
                '@parsedTitle0',
                '@forum0',
                '<numberBetween(1, 1000000)>',
                '<dateTimeImmutable("now", "UTC")>',
                '<dateTimeImmutable("now", "UTC")>',
                true
            ]
        ]
    ],
];
