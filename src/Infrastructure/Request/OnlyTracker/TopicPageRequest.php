<?php

namespace App\Infrastructure\Request\OnlyTracker;

use App\Infrastructure\Request\RequestInterface;

class TopicPageRequest implements RequestInterface
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function url(): string
    {
        return '/forum/viewforum.php';
    }

    public function method(): string
    {
        return 'GET';
    }

    public function options(): array
    {
        return [
            'query' => [
                't' => $this->id
            ]
        ];
    }
}
