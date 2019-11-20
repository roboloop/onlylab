<?php

namespace App\Service\Parser\Html;

use App\Dto\RawTopicDto;
use App\Service\Parser\Title\GenreParser;
use App\Service\Parser\Title\QualityParser;
use App\Service\Parser\Title\SiteParser;
use Symfony\Component\DomCrawler\Crawler;

class ForumHtmlParser
{
    private $siteParser;
    private $genreParser;
    private $qualityParser;

    // public function __construct(
    //     SiteParser $siteParser,
    //     GenreParser $genreParser,
    //     QualityParser $qualityParser
    // ) {
    //     $this->siteParser       = $siteParser;
    //     $this->genreParser      = $genreParser;
    //     $this->qualityParser    = $qualityParser;
    // }

    public function rawParticleTopicDto(string $content)
    {
        $entities = $this->forumLinesDto($content);

        $forumId = $this->forumId($content);
        $forumTitle = $this->forumTitle($content);

        foreach ($entities as $entity) {
            $entity->setForumId($forumId);
            $entity->setForumTitle($forumTitle);
        }

        return $entities;
    }

    public function forumId(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        $href       = $body->attr('href');

        preg_match('~f=(\d+)~', $href, $matches);

        return isset($matches[1]) ? (int) $matches[1] : null;
    }

    public function forumTitle(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        $forumTitle = $body->getNode(0)->nodeValue;

        return $forumTitle;
    }

    public function forumLinesDto(string $content)
    {
        $crawler = new Crawler($content);

        $lines = $crawler->filterXPath('//table[@class="forumline forum"]/tr[contains(@id, "tr-")]');

        return $this->filter($lines)->each(function (Crawler $node) {
            return $this->forumLineDto($node);
        });
    }

    protected function filter(Crawler $lines)
    {
        return $lines->reduce(function (Crawler $node) {
            // This advert is missing the file size. Delete it.
            return null !== $node->children()->getNode(2)->firstChild;
        });
    }

    protected function forumLineDto(Crawler $line)
    {
        $line = $line->children();

        $trackerId      = $line->getNode(0)->getAttribute('id');
        $rawTitle   = $line->getNode(1)->firstChild->nodeValue;
        $size       = $line->getNode(2)->firstChild->nodeValue;

        // TODO: author parse
        $authorId   = null;
        $authorName = null;

        return (new RawTopicDto)
            ->setTrackerId($trackerId)
            ->setRawTitle($rawTitle)
            ->setSize($size)
            ->setAuthorId($authorId)
            ->setAuthorName($authorName);
    }

    public function pages(string $content)
    {
        $crawler = new Crawler($content);

        $node = $crawler->filterXPath('//div[@id="pagination"]/p');
        $value = $node->getNode(0)->nodeValue;

        preg_match('~(\d+)\D+(\d+)~', $value, $matches);

        if (isset($matches[1]) and isset($matches[2])) {
            return [(int) $matches[1], (int) $matches[2]];
        }

        return [null, null];
    }
}
