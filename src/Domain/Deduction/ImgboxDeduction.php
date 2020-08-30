<?php

declare (strict_types=1);

namespace OnlyTracker\Domain\Deduction;

use OnlyTracker\Domain\Entity\Enum\ImageFormat;

final class ImgboxDeduction implements OriginalUrlDeductionInterface
{
    public function deduct(string $frontUrl, array $context = [])
    {
        // if (isset($context['format']) && $context['format'] === ImageFormat::POSTER) {
        //     return $frontUrl;
        // }
        
        $isFull = preg_match('#o\.[^.]+$#', $frontUrl);
        if ($isFull) {
            return $frontUrl;
        }

        $step1 = preg_replace('#(?<=//)thumbs#', 'images', $frontUrl, -1, $count);
        if (1 !== $count) {
            return $frontUrl;
        }
        
        $step2 = preg_replace('#_t(?=\.\w+$)#', '_o', $step1, -1, $count);
        if (1 !== $count) {
            return $frontUrl;
        }
        
        return $step2;
    }

    public function support(string $frontUrl, array $context = []): bool
    {
        return (bool) preg_match('#[/.]imgbox\.com[/.]#', $frontUrl);
    }
}
