<?php

declare (strict_types = 1);

namespace OnlyTracker\Infrastructure\Symfony\Constraints;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

/**
 * @Annotation
 */
class QueryString extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(['allowNull' => true]),
            new Assert\Type('string'),
        ];
    }
}