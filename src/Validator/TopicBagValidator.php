<?php

namespace App\Validator;

use App\Bag\Bag;
use App\Contract\Validator\ValidatorInterface;
use Exception;

class TopicBagValidator implements ValidatorInterface
{
    /**
     * @param \App\Bag\Bag $bag
     *
     * @throws \Exception
     */
    public function validate(Bag $bag)
    {
        if ($bag->has('title') and $bag->has('size') and $bag->has('trackerCreatedAt') and $bag->has('trackerId') and $bag->has('releaseAt'))
            return;

        throw new Exception('Not all data');
    }
}
