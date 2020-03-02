<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Shared\Symfony\ArgumentResolver;

use OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\IncomingDataInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SimpleDto implements IncomingDataInterface
{
    /**
     * @Assert\NotBlank()
     */
    private $attr1;
    private $attr2;
    private $attr3;
}
