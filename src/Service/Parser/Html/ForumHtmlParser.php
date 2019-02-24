<?php

namespace App\Service\Parser\Html;

use App\Dto\ForumLineDto;
use App\Service\Parser\Title\GenreParser;
use App\Service\Parser\Title\QualityParser;
use App\Service\Parser\Title\SiteParser;
use Symfony\Component\DomCrawler\Crawler;

class ForumHtmlParser
{
    private $siteParser;
    private $genreParser;
    private $qualityParser;

    public function __construct(
        SiteParser $siteParser,
        GenreParser $genreParser,
        QualityParser $qualityParser
    ) {
        $this->siteParser       = $siteParser;
        $this->genreParser      = $genreParser;
        $this->qualityParser    = $qualityParser;
    }

    public function forumLines(string $content)
    {
        $crawler = new Crawler($content);

        $lines = $crawler->filterXPath('//table[@class="forumline forum"]/tr[contains(@id, "tr-")]');

        return $this->filter($lines)->each(function (Crawler $node) {
            return $this->forumLine($node);
        });
    }

    protected function filter(Crawler $lines)
    {
        return $lines->reduce(function (Crawler $node) {
            // This advert is missing the file size. Delete it.
            return null !== $node->children()->getNode(2)->firstChild;
        });
    }

    protected function forumLine(Crawler $line)
    {
        $trackerId      = $line->getNode(0)->getAttribute('id');
        $title      = $line->getNode(1)->firstChild->nodeValue;
        $size       = $line->getNode(2)->firstChild->nodeValue;

        // TODO: author parse
        $authorId   = null;
        $authorName = null;

        return (new ForumLineDto)
            ->setTrackerId($trackerId)
            ->setTitle($title)
            ->setSize($size)
            ->setAuthorId($authorId)
            ->setAuthorName($authorName);
    }
}
