<?php

namespace App\Service\Transformer;

/**
 * Cleaning input HTML markup from extraneous characters
 *
 * @package App\Service\Transformer
 */
class TextCleaner
{
    /**
     * Clearing the input string of line breaks and tabs
     *
     * @param string $content
     *
     * @return string
     */
    public function clearWhitespaces(string $content): string
    {
        return str_replace(["\n", "\r\n", "\r", "\t"], '', $content);
    }
}
