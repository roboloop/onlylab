<?php

namespace App\Domain\Deduction;

interface OriginalUrlDeductionInterface
{
    /**
     * @param string $frontUrl
     * @param array  $context
     *
     * @return string|null
     * @throws NoOriginalUrlDeductionSupportsException
     */
    public function deduct(string $frontUrl, array $context = []);

    /**
     * @param string $frontUrl
     *
     * @return bool
     */
    public function support(string $frontUrl): bool;
}
