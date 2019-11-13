<?php

namespace App\Domain\Service;

interface OriginalUrlDeductorInterface
{
    public function deduct(string $frontUrl, ?string $reference);

    public function support(string $frontUrl, ?string $reference);
}
