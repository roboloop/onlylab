<?php

namespace App\Application\Parser;

use App\Application\Dto\RawImageDto;
use App\Application\Dto\RawTopicDto;
use Closure;
use Symfony\Component\DomCrawler\Crawler;

class TopicHtmlParser
{
    public function parse(string $content)
    {
        return new RawTopicDto(
            $this->exId($content),
            $this->forumExId($content),
            $this->forumTitle($content),
            $this->rawTitle($content),
            $this->size($content),
            $this->exCreatedAt($content),
            $this->images($content)
        );
    }

    private function exId(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        preg_match('~t=(\d+)~', $body->getNode(0)->getAttribute('href'), $matches);

        return $matches[1];
    }

    private function forumExId($content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//div[@id="main_content_wrap"]//table//table//td[@class="nav"]/a');
        $href       = $body->last()->getNode(0)->getAttribute('href');

        preg_match('~f=(\d+)~', $href, $matches);

        return $matches[1] ?? null;
    }

    private function forumTitle(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//div[@id="main_content_wrap"]//table//table//td[@class="nav"]/a');
        $text       = $body->last()->getNode(0)->nodeValue;

        return $text;
    }

    private function rawTitle(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        $rawTitle   = $body->getNode(0)->nodeValue;

        return $rawTitle;
    }

    private function size(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table[@class="attach bordered med"]/tr');
        $size       = $body->getNode(4)->lastChild->nodeValue ?? null;

        return $size;
    }

    private function exCreatedAt(string $content)
    {
        // TODO:
        return '';
    }

    private function images(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table[@class="topic"]//div[@class="post_body"]');
        $images[]   = $this->posters($body);
        $images[]   = $this->underSpoiler($body);

        return array_merge(...$images);
    }

    private function underSpoiler(Crawler $body)
    {
        // Get all the spoilers
        $spoilers       = $body->filterXPath('//div[@class="sp-body"]');
        $totalSpoilers  = $spoilers->count();
        $images         = [];

        // Iterate over all spoilers
        for ($i = 0; $i < $totalSpoilers; $i++) {
            $node   = $spoilers->eq($i);
            $header = $node->first()->getNode(0)->nodeValue;
            $vars   = $node->filterXPath('//var[@class="postImg"]');
            $data   = $vars->each(Closure::fromCallable([$this, 'getFromUrlAndReference']));

            foreach ($data as [$frontUrl, $reference]) {
                $images[] = new RawImageDto(
                    $this->sanitize($frontUrl),
                    $this->sanitize($reference),
                    RawImageDto::PLACE_UNDER_SPOILER,
                    $this->sanitize($header)
                );
            }
        }

        return $images;
    }

    private function getFromUrlAndReference(Crawler $crawler)
    {
        $frontUrl   = $crawler->getNode(0)->getAttribute('title');
        $reference  = $crawler->parents()->getNode(0)->getAttribute('href');

        return [$frontUrl, $reference];
    }

    private function posters(Crawler $body)
    {
        // Get all images
        $crawlerImages = $body->filterXPath('//var[@class="postImg"]');

        // Everything that is not under the spoiler is a poster
        // Banner filtering will be done later
        $filtered = $crawlerImages->reduce(Closure::fromCallable([$this, 'excludeUnderSpoiler']));
        $images = [];

        foreach ($filtered as $item) {
            $frontUrl = $item->getAttribute('title');
            $images[] = new RawImageDto($this->sanitize($frontUrl), null, RawImageDto::PLACE_ON_PAGE);
        }

        return $images;
    }

    private function excludeUnderSpoiler(Crawler $node)
    {
        $parents = $node->parents();
        $include = true;
        for ($i = 0; $i < $parents->count(); $i++) {
            $class = $parents->getNode($i)->getAttribute('class');
            if ($class === 'sp-wrap') {
                $include = false;
                break;
            }

            if ($class === 'post_body') {
                break;
            }
        }

        return $include;
    }

    private function sanitize($data)
    {
        if (is_string($data)) {
            $data = trim($data);
            $data = '' === $data ? null : $data;
        }

        return $data;
    }
}
