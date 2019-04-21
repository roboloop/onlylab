<?php

namespace App\Service\Request\Image;

use App\Constant\Method;
use App\Service\Request\Request;

class FastpicRequest extends Request
{
    private $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return Method::GET;
    }

    public function getOptions()
    {
        return [
            'headers' => [
                'Referer' => 'https://fastpic.ru/'
            ]
        ];
    }
}
