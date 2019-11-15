<?php

namespace App\Domain\Deduction;

class FastpicDeduction implements OriginalUrlDeductionInterface
{
    public function deduct(string $frontUrl, array $context = [])
    {
        $frontUrl = preg_replace('~thumb~', 'big', $frontUrl);
        $frontUrl = preg_replace('~jpeg$~', 'jpg', $frontUrl);

        return $frontUrl . '?noht=1';
    }

    public function support(string $frontUrl): bool
    {
        return preg_match('~fastpic\.ru~', $frontUrl);

    }
}
