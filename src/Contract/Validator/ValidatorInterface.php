<?php

namespace App\Contract\Validator;

use App\Bag\Bag;

interface ValidatorInterface
{
    public function validate(Bag $bag);
}
