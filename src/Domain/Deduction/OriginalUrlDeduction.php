<?php

namespace App\Domain\Deduction;

class OriginalUrlDeduction implements OriginalUrlDeductionInterface
{
    /**
     * @var \App\Domain\Deduction\OriginalUrlDeductionInterface[]
     */
    private $deductions;

    public function __construct($deductions)
    {
        $this->deductions = iterator_to_array($deductions);
    }


    public function deduct(string $frontUrl, array $context = [])
    {
        $deduction = $this->getDeduction($frontUrl);

        if (null !== $deduction) {
            return $deduction->deduct($frontUrl, $context);
        }

        throw new NoOriginalUrlDeductionSupportsException;
    }

    public function support(string $frontUrl): bool
    {
        $deduction = $this->getDeduction($frontUrl);

        return $deduction instanceof OriginalUrlDeductionInterface;
    }

    private function getDeduction($frontUrl): ?OriginalUrlDeductionInterface
    {
        foreach ($this->deductions as $deduction) {
            if ($deduction->support($frontUrl)) {
                return $deduction;
            }
        }

        return null;
    }
}
