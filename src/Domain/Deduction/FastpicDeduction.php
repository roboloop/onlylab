<?php

namespace OnlyTracker\Domain\Deduction;

use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\DomCrawler\Crawler;

class FastpicDeduction implements OriginalUrlDeductionInterface
{
    private RequestSenderInterface $requestSender;

    public function __construct(RequestSenderInterface $requestSender)
    {
        $this->requestSender = $requestSender;
    }

    public function deduct(string $frontUrl, array $context = [])
    {
        try {
            if (!empty($context['reference'])) {
                $url = $context['reference'];

                $content = $this->requestSender->sendRaw($url);

                $script = (new Crawler($content))->filterXPath('//body/script[contains(text(), "loading_img")]');

                if ($script->count()) {
                    $value = $script->getNode(0)->nodeValue;
                    preg_match('#http[^\']+#', $value, $matched);
                    if ($matched[0]) {
                        return $matched[0];
                    }
                }

                if (preg_match('#\.html$#', $url)) {
                    $frontUrl = preg_replace('~thumb~', 'big', $frontUrl);
                    $frontUrl = preg_replace('~jpeg$~', 'jpg', $frontUrl);
                    // $url = preg_replace('~\.html$~', '', $frontUrl);

                    return $frontUrl . '?noht=1';
                }
            }
        } catch (\Exception $e) {

        }

        return preg_replace('#^http(?!s)#', 'https', $url);
    }

    public function support(string $frontUrl, array $context = []): bool
    {
        return preg_match('#[/.]fastpic\.ru[/.]#', $frontUrl);
    }
}
