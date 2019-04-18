<?php

namespace App\Service\Sanitizer;

use App\Contract\Service\Sanitizer\SanitizerInterface;
use App\Service\Transformer\ContentDecoder;
use App\Service\Transformer\TextCleaner;

class HtmlSanitizer implements SanitizerInterface
{
    private $contentDecoder;
    private $textCleaner;

    public function __construct(ContentDecoder $contentDecoder, TextCleaner $textCleaner)
    {
        $this->contentDecoder = $contentDecoder;
        $this->textCleaner = $textCleaner;
    }

    public function sanitize(string $content)
    {
        return $this->textCleaner->clearWhitespaces(
            $this->contentDecoder->decode($content)
        );
    }
}
