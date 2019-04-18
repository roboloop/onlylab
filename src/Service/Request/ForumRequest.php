<?php

namespace App\Service\Request;

use App\Constant\Method;

class ForumRequest extends Request
{
    private $id;
    private $page;
    private $offset;

    public function __construct($id, $page = 1)
    {
        $this->id = $id;
        $this->page = $page;

        $this->initialize();
    }

    private function initialize()
    {
        $this->offset = 50 * ($this->page - 1);
    }

    public function getUri()
    {
        return '/forum/viewforum.php';
    }

    public function getMethod()
    {
        return Method::GET;
    }

    public function getOptions()
    {
        return [
            'query' => [
                'f' => $this->id,
                'start' => $this->offset,
            ]
        ];
    }
}
