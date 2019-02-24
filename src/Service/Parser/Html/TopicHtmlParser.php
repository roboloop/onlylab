<?php

namespace App\Service\Parser\Html;

use Symfony\Component\DomCrawler\Crawler;

class TopicHtmlParser
{
    public function pics(string $content)
    {
        $crawler = new Crawler($content);
        $body = $crawler->filterXPath('//div[@id="page_content"]//table[@class="topic"]//tbody[contains(@id, "post_")]//div[@class="post_body"]');
        $vars = $body->filterXPath('//var[@class="postImg"]');

        foreach ($vars as $var) {
            $attr[] = $var->getAttribute('title');
        }

        return [];
    }
}
