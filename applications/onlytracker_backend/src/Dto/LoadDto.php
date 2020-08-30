<?php

declare (strict_types = 1);

namespace OnlyTracker\BackEnd\Dto;

use OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\IncomingDataInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class LoadDto implements IncomingDataInterface
{
    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private $forums;
    
    private $start;

    private $end;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->start === null && $this->end === null) {
            $context
                ->buildViolation('Start end end value cannot be null')
                ->atPath('start')
                ->addViolation();
        }
        
        if (null !== $this->start && null !== $this->end && $this->start > $this->end) {
            $context
                ->buildViolation('Start value cannot be greater than end')
                ->atPath('start')
                ->addViolation();
        }
    }

    public function forums()
    {
        return $this->forums;
    }
    
    public function start()
    {
        return $this->start;
    }

    public function end()
    {
        return $this->end;
    }
}
