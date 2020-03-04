<?php

namespace OnlyTracker\Infrastructure\Request\OnlyTracker;

use OnlyTracker\Infrastructure\Request\RequestInterface;

class ForumPageRequest implements RequestInterface
{
    private $id;
    private $page;
    private $offset;

    public function __construct($id, int $page = 1)
    {
        $this->id       = $id;
        $this->page     = $page;
        $this->offset   = 50 * ($this->page - 1);
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
                'f' => $this->id,
                'start' => $this->offset,
            ]
        ];
    }
}
