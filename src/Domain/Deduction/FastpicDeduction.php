<?php

namespace App\Domain\Deduction;

class FastpicDeduction implements OriginalUrlDeductionInterface
{
    public function deduct(string $frontUrl, array $context = [])
    {
        $url = preg_replace('~thumb~', 'big', $frontUrl);
        $url = preg_replace('~jpeg$~', 'jpg', $frontUrl);

        return $url . '?noht=1';
    }

    public function support(string $frontUrl)
    {
        return preg_match('~fastpic\.ru~', $frontUrl);

    }
}
