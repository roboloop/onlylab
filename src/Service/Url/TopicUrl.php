<?php

namespace App\Service\Url;

use App\Service\Bag\ParameterBag;

class TopicUrl
{
    private $parameterBag;

    public function __construct(ParameterBag $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function url($id)
    {
        return $this->parameterBag->getBaseUrl() . 'forum/viewtopic.php?t=' . $id;
    }
}
