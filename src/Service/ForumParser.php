<?php

namespace App\Service;

use App\Entity\Film;
use App\Parser\GenreParser;
use App\Parser\QualityParser;
use App\Parser\SiteParser;
use Symfony\Component\DomCrawler\Crawler;

class ForumParser
{
    /** @var \App\Parser\SiteParser */
    private $siteParser;

    /** @var \App\Parser\GenreParser */
    private $genreParser;

    /** @var \App\Parser\QualityParser */
    private $qualityParser;

    public function __construct(
        SiteParser $siteParser,
        GenreParser $genreParser,
        QualityParser $qualityParser)
    {
        $this->siteParser       = $siteParser;
        $this->genreParser      = $genreParser;
        $this->qualityParser    = $qualityParser;
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
                ->setHref($href)
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
