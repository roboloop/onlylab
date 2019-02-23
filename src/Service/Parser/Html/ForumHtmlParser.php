<?php

namespace App\Service\Parser\Html;

use App\Service\Parser\Title\GenreParser;
use App\Service\Parser\Title\QualityParser;
use App\Service\Parser\Title\SiteParser;
use Symfony\Component\DomCrawler\Crawler;

class ForumHtmlParser
{
    private $siteParser;
    private $genreParser;
    private $qualityParser;
    private $forumLineParser;

    public function __construct(
        SiteParser $siteParser,
        GenreParser $genreParser,
        QualityParser $qualityParser,
        ForumLineParser $forumLineParser
    ) {
        $this->siteParser       = $siteParser;
        $this->genreParser      = $genreParser;
        $this->qualityParser    = $qualityParser;
        $this->forumLineParser  = $forumLineParser;
    }

    public function forumLines(string $content)
    {
        $crawler = new Crawler($content);

        $lines = $crawler->filterXPath('//table[@class="forumline forum"]/tr[contains(@id, "tr-")]');

        return $this->filter($lines)->each(function (Crawler $node) {
            return $this->forumLineParser->parse($node);
        });
    }

    protected function filter(Crawler $lines)
    {
        return $lines->reduce(function (Crawler $node) {
            // This advert is missing the file size. Delete it.
            return null !== $node->children()->getNode(2)->firstChild;
        });
    }
}
