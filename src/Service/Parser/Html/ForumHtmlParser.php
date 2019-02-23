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
        ForumLineParser $forumLineParser)
    {
        $this->siteParser       = $siteParser;
        $this->genreParser      = $genreParser;
        $this->qualityParser    = $qualityParser;
        $this->forumLineParser  = $forumLineParser;
    }

    public function forumLines(string $content)
    {
        $crawler = new Crawler($content);

        $lines = $crawler->filterXPath('//table[@class="forumline forum"]/tr[contains(@id, "tr-")]');

        return $lines->each(function (Crawler $node) {
            $nodes      = $node->children();

            $id         = $nodes->getNode(0)->getAttribute('id');
            $href       = $nodes->getNode(1)->firstChild->getAttribute('href');
            $title      = $nodes->getNode(1)->firstChild->nodeValue;
            $size       = $nodes->getNode(2)->firstChild->nodeValue;
            $createdAt  = $nodes->getNode(4)->firstChild->firstChild->nodeValue;

            return (new Film())
                ->setId($id)
                ->setHref($this->urlResolver->url($href))
                ->setTitle($title)
                ->setSize($size)
                ->setCreatedAt(new \DateTime($createdAt))
                ->setSite($this->siteParser->parse($title))
                ->setGenre($this->genreParser->parse($title))
                ->setQuality($this->qualityParser->parse($title));
        });
    }
}
