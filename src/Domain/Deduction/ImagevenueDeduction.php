<?php

declare(strict_types=1);

namespace OnlyTracker\Domain\Deduction;

use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ImagevenueDeduction implements OriginalUrlDeductionInterface
{
    private RequestSenderInterface $requestSender;
    private HttpClientInterface $httpClient;

    public function __construct(RequestSenderInterface $requestSender, HttpClientInterface $httpClient)
    {
        $this->requestSender = $requestSender;
        $this->httpClient = $httpClient;
    }

    public function deduct(string $frontUrl, array $context = [])
    {
        try {
            if (!empty($context['reference'])) {
                $url = $context['reference'];
                $response = $this->httpClient->request('GET', $url, [
                    'max_redirects' => 3,
                    'headers' => ['accept' => 'text/html'],
                ]);

                $content = $response->getContent();

                $imgUrl = (new Crawler($content))->filterXPath('//body//img[contains(@src, "cdn-images")]');
                if ($imgUrl->count()) {
                    $value = $imgUrl->getNode(0)->attributes->getNamedItem('src')->nodeValue;

                    return $value;
                }
            }
        } catch (\Throwable $e) {

        }

        return preg_replace('#^http(?!s)#', 'https', $url ?? $frontUrl);
    }

    public function support(string $frontUrl, array $context = []): bool
    {
        return preg_match('#[/.]imagevenue\.com[/.]#', $frontUrl);
    }
}
