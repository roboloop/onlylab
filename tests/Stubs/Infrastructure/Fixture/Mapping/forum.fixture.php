<?php

return [
    \OnlyTracker\Domain\Entity\Forum::class => [
        'forum0' => [
            '__construct' => [
                '<uuid()>',
                '<numberBetween(1, 100)>',
                '<words(3, true)>',
                '<dateTimeImmutable("now", "UTC")>',
            ],
        ],
    ],
];
