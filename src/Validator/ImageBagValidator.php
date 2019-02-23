<?php

namespace App\Validator;

use App\Bag\Bag;
use App\Contract\Validator\ValidatorInterface;
use RuntimeException;

class ImageBagValidator implements ValidatorInterface
{
    public function validate(Bag $bag)
    {
        foreach ($bag->all() as $imageBag) {
            if (!$imageBag instanceof Bag) {
                throw new RuntimeException();
            }

            if ($imageBag->has('type') and $imageBag->has('preview') and $imageBag->has('reference') and $imageBag->has('original') and $imageBag->has('host')) {
                continue;
            }

            throw new RuntimeException('Not all data');
        }
    }
}
