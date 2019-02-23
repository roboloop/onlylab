<?php

namespace App\Service\Parser\Html;

use Symfony\Component\DomCrawler\Crawler;

class ForumLineParser
{
    public function parse(Crawler $node)
    {

        return true;
    }

    public function temp()
    {
        $id         = $line->getNode(0)->getAttribute('id');
        $href       = $line->getNode(1)->firstChild->getAttribute('href');
        $title      = $line->getNode(1)->firstChild->nodeValue;
        $size       = $line->getNode(2)->firstChild->nodeValue;
        $createdAt  = $line->getNode(4)->firstChild->firstChild->nodeValue;

        return (new Film())
            ->setId($id)
            ->setHref($this->urlResolver->url($href))
            ->setTitle($title)
            ->setSize($size)
            ->setCreatedAt(new \DateTime($createdAt))
            ->setSite($this->siteParser->parse($title))
            ->setGenre($this->genreParser->parse($title))
            ->setQuality($this->qualityParser->parse($title));
    }
}
