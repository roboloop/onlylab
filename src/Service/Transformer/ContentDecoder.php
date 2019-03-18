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

        // When debugging, it displays values incorrectly, but reads them correctly.
        return $content;

        // When debugging, it displays the values normally, but it reads them incorrectly.
        return iconv($encoding, self::TO_ENCODING, $content);
    }
}
