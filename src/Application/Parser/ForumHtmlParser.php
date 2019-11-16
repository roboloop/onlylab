<?php

namespace App\Application\Parser;

use App\Application\Dto\RawForumDto;
use App\Application\Dto\RawTopicDto;
use Symfony\Component\DomCrawler\Crawler;

class ForumHtmlParser
{
    /**
     * @param string $content
     *
     * @return \App\Application\Dto\RawTopicDto[]
     */
    public function parseTopicsHeaders(string $content)
    {
        $crawler = new Crawler($content);

        $lines = $crawler->filterXPath('//table[@class="forumline forum"]/tr[contains(@id, "tr-")]');
        $forum = $this->forum($content);

        $filterFromNotTopics = function (Crawler $node) {
            // This is ad, if no torrent size exists.
            return null !== $node->children()->getNode(2)->firstChild;
        };

        $createTopic = function (Crawler $line) use ($forum) {
            $line = $line->children();

            $exId           = $line->getNode(0)->getAttribute('id');
            $rawTitle       = $line->getNode(1)->firstChild->nodeValue;
            $size           = $line->getNode(2)->firstChild->nodeValue;
            $exCreatedAt    = $line->getNode(3)->firstChild->nodeValue;

            return new RawTopicDto(
                $this->sanitize($exId),
                $this->sanitize($rawTitle),
                $this->sanitize($size),
                $this->sanitize($exCreatedAt),
                $this->sanitize($forum)
            );
        };

        return $lines->reduce($filterFromNotTopics)->each($createTopic);
    }

    private function sanitize($data)
    {
        if (is_string($data)) {
            $data = trim($data);
            $data = '' === $data ? null : $data;
        }

        return $data;
    }

    public function parsePageState(string $content)
    {
        // TODO:
    }

    private function forum(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        $href       = $body->attr('href');

        preg_match('~f=(\d+)~', $href, $matches);

        $exId = $matches[1] ?? null;

        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        $title      = $body->getNode(0)->nodeValue;

        return new RawForumDto($exId, $title);
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
