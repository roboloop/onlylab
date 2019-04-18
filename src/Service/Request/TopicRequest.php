<?php

namespace App\Service\Request;

use App\Constant\Method;

class TopicRequest extends Request
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getUri()
    {
        return '/forum/viewtopic.php';
    }

    public function getMethod()
    {
        return Method::GET;
    }

    public function getOptions()
    {
        return [
            'query' => [
                't' => $this->id
            ]
        ];
    }
}
