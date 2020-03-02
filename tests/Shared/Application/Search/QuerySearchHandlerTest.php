<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Shared\Application\Search;

use OnlyTracker\Shared\Application\Search\QuerySearchHandler;
use PHPUnit\Framework\TestCase;

class QuerySearchHandlerTest extends TestCase
{
    /** @var \OnlyTracker\Shared\Application\Search\QuerySearchHandler */
    private $handler;

    protected function setUp()
    {
        $this->handler = new QuerySearchHandler();
    }

    /**
     * @dataProvider data
     */
    function testExplodeToWords($input, $expected)
    {
        $output = $this->handler->explodeIntoWords($input);

        $this->assertEquals($expected, $output);
    }

    function data()
    {
        return [
            [
                '   ',
                [ ],
            ],
            [
                'movie',
                [
                    'movie',
                ],
            ],
            [
                'word again word',
                [
                    'word',
                    'again',
                ],
            ],
            [
                '  awesome  movie go  look',
                [
                    'awesome',
                    'movie',
                    'go',
                    'look',
                ],
            ],
            [
                '"lets go see" the movie',
                [
                    'lets go see',
                    'the',
                    'movie',
                ],
            ],
            [
                'lets "go"  "see awesome" movie',
                [
                    'lets',
                    'go',
                    'see awesome',
                    'movie',
                ],
            ],
            [
                'Text in a "foreign" language ',
                [
                    'Text in a',
                    'foreign',
                    'language',
                ]
            ]
        ];
    }
}
