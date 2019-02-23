<?php

namespace App\Service\Transformer;

class TextCleaner
{
    public function clearWhitespaces(string $content): string
    {
        return str_replace(["\n", "\r\n", "\r", "\t"], '', $content);
    }
}
