<?php

namespace App\Infrastructure\Assert;

use InvalidArgumentException as BaseInvalidArgumentException;
use Assert\AssertionFailedException;

class InvalidArgumentException extends BaseInvalidArgumentException implements AssertionFailedException
{
    private $propertyPath;
    private $value;
    private $constraints;

    public function __construct($message, $code, string $propertyPath = null, $value = null, array $constraints = [])
    {
        parent::__construct($message, $code);

        $this->propertyPath = $propertyPath;
        $this->value        = $value;
        $this->constraints  = $constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
