<?php

declare (strict_types=1);

namespace OnlyTracker\Application\Normalizers;

use OnlyTracker\Domain\Entity\Forum;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ForumNormalizer implements NormalizerInterface
{
    /**
     * @param $forum Forum
     * {@inheritDoc}
     */
    public function normalize($forum, string $format = null, array $context = [])
    {
        return [
            'id'    => $forum->getId(),
            'title' => $forum->getTitle(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Forum;
    }
}
