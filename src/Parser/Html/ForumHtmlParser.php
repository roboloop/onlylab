<?php

namespace App\Parser\Html;

use App\Entity\Film;
use App\Parser\Title\GenreParser;
use App\Parser\Title\QualityParser;
use App\Parser\Title\SiteParser;
use App\Util\UrlResolver;
use Symfony\Component\DomCrawler\Crawler;

class ForumHtmlParser
{
    /** @var \App\Parser\Title\SiteParser */
    private $siteParser;

    /** @var \App\Parser\Title\GenreParser */
    private $genreParser;

    /** @var \App\Parser\Title\QualityParser */
    private $qualityParser;

    /** @var \App\Util\UrlResolver */
    private $urlResolver;

    public function __construct(
        SiteParser $siteParser,
        GenreParser $genreParser,
        QualityParser $qualityParser,
        UrlResolver $urlResolver)
    {
        $this->siteParser       = $siteParser;
        $this->genreParser      = $genreParser;
        $this->qualityParser    = $qualityParser;
        $this->urlResolver      = $urlResolver;
    }

    public function getParsed($content): array
    {
        $crawler = new Crawler($this->cleaning($content));

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

    private function cleaning($content): string
    {
        return str_replace(["\n", "\r\n", "\r", "\t"], '', $content);
    }
}
