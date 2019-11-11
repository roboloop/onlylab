<?php

namespace App\Infrastructure\Assert;

use Assert\Assertion as BaseAssertion;

class Assertion extends BaseAssertion
{
    protected static $exceptionClass = InvalidArgumentException::class;
}
