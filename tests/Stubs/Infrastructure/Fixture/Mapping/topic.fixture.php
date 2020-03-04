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
    \OnlyTracker\Domain\Entity\ObjectValue\Size::class => [
        'size0' => [
            '__construct' => [
                '<numberBetween(1, 1000000)>',
            ]
        ],
    ],

    \OnlyTracker\Domain\Entity\Topic::class => [
        'topic0' => [
            '__construct' => [
                '<word()>',
                '@parsedTitle0',
                '@forum0',
                '@size0',
                '<dateTimeImmutable("now", "UTC")>',
                '<dateTimeImmutable("now", "UTC")>',
                true
            ]
        ]
    ],
];
