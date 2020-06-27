<?php

declare (strict_types = 1);

namespace OnlyTracker\Infrastructure\Symfony\Normalizer;

use OnlyTracker\Shared\Domain\ValueObject\Enum;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EnumNormalizer implements NormalizerInterface
{
    public function normalize($data, string $format = null, array $context = [])
    {
        /** @var $data Enum */
        return $data->value();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Enum;
    }
}
