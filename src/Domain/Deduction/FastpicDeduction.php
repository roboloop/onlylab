<?php

namespace OnlyTracker\Domain\Deduction;

use OnlyTracker\Infrastructure\Request\RequestSenderInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FastpicDeduction implements OriginalUrlDeductionInterface
{
    private RequestSenderInterface $requestSender;
    private HttpClientInterface $onlyTrackerClient;

    public function __construct(RequestSenderInterface $requestSender, HttpClientInterface $onlyTrackerClient)
    {
        $this->requestSender = $requestSender;
        $this->onlyTrackerClient = $onlyTrackerClient;
    }

    public function deduct(string $frontUrl, array $context = [])
    {
        try {
            if (!empty($context['reference'])) {
                $url = $context['reference'];
                if (preg_match('#\.(?:jpeg|jpg|png)$#', $url)) {
                    $response = $this->onlyTrackerClient->request('GET', $url, [
                        'max_redirects' => 0,
                        'headers' => ['accept' => 'text/html'],
                    ]);
                    if ($response->getStatusCode() === 302) {
                        $url = $response->getHeaders(false)['location'][0];
                    }
                }

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
