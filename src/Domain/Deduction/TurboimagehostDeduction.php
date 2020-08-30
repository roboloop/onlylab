<?php

declare (strict_types=1);

namespace OnlyTracker\Domain\Deduction;

use OnlyTracker\Infrastructure\Request\RequestInterface;
use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\DomCrawler\Crawler;

class TurboimagehostDeduction implements OriginalUrlDeductionInterface
{
    private RequestSenderInterface $requestSender;

    public function __construct(RequestSenderInterface $requestSender)
    {
        $this->requestSender = $requestSender;
    }

    public function deduct(string $frontUrl, array $context = [])
    {
        if (empty($context['reference'])) {
            return $frontUrl;
        }

        $request = new class($context['reference']) implements RequestInterface {
            private string $reference;
            public function __construct(string $reference) {
                $this->reference = $reference;
            }

            public function url(): string {
                return $this->reference;
            }

            public function method(): string {
                return 'GET';
            }

            public function options(): array {
                return [];
            }
        };

        $content = $this->requestSender->send($request);

        $meta = (new Crawler($content))->filterXPath('//html/head/meta[@property="og:image"]');

        if ($meta->count()) {
            return $meta->attr('content');
        }

        return $frontUrl;
    }

    public function support(string $frontUrl, array $context = []): bool
    {
        $toMatch[] = $frontUrl;
        if (!empty($context['reference'])) {
            $toMatch[] = $context['reference'];
        }

        foreach ($toMatch as $match) {
            if (preg_match('#[/.]turboimagehost\.com/|turboimg\.net/#', $match)) {
                return true;
            }
        }

        return false;
    }
}
