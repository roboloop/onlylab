<?php

namespace App\HtmlParser;

use Symfony\Component\DomCrawler\Crawler;

class PostHtmlParser
{
    public function picUrls(string $content): array
    {
        $crawler = new Crawler($this->cleaning($content));

        $body = $crawler->filterXPath('//div[@id="page_content"]//table[@class="topic"]//tbody[contains(@id, "post_")]//div[@class="post_body"]');

        // $vars = $body->filterXPath('//div[@class="sp-wrap"]//var[@class="postImg"]');

        $vars = $body->filterXPath('//var[@class="postImg"]');

        foreach ($vars as $var) {
            $attr[] = $var->getAttribute('title');
        }

        return $attr ?? [];
    }

    private function cleaning($content): string
    {
        return str_replace(["\n", "\r\n", "\r", "\t"], '', $content);
    }
}
