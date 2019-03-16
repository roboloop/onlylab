<?php

namespace App\Service\Transformer;

class ContentDecoder
{
    const DEFAULT_ENCODING = 'windows-1251';
    const TO_ENCODING = 'UTF-8';

    /**
     * Converting the input string encoding to UTF-8
     *
     * @param string      $content
     * @param null|string $encoding
     *
     * @return null|string|string[]
     */
    public function decode(string $content, ?string $encoding = null)
    {
        // TODO:

        $encoding = $encoding ?: self::DEFAULT_ENCODING;

        return $content;

        return mb_convert_encoding($content, $encoding, self::TO_ENCODING);

        return iconv($encoding, self::TO_ENCODING, $content);
    }
}
