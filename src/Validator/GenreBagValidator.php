<?php

namespace App\Validator;

use App\Bag\Bag;
use App\Contract\Validator\ValidatorInterface;
use RuntimeException;

class GenreBagValidator implements ValidatorInterface
{
    public function validate(Bag $bag)
    {
        foreach ($bag->all() as $genreBag) {
            if (!$genreBag instanceof Bag) {
                throw new RuntimeException();
            }

            if ($genreBag->has('title') and $genreBag->has('status')) {
                continue;
            }

            throw new RuntimeException('Not all data');
        }
    }
}
