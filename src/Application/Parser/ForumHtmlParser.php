<?php

namespace App\Application\Parser;

use App\Application\Dto\RawForumDto;
use App\Application\Dto\RawTopicDto;
use Closure;
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

        // Layout is broken. Look only "forumline forum"
        $lines = $crawler->filterXPath('//table[@class="forumline forum"]/tr[contains(@id, "tr-")]');
        $forum = $this->forum($content);

        $rawData = $lines
            ->reduce(Closure::fromCallable([$this, 'filterFromNotTopics']))
            ->each(Closure::fromCallable([$this, 'collectRawTopic']));

        $rawTopics = [];

        foreach ($rawData as [$exId, $rawTitle, $size, $exCreatedAt]) {
            $rawTopics[] = new RawTopicDto(
                $this->sanitize($exId),
                $this->sanitize($rawTitle),
                $this->sanitize($size),
                $this->sanitize($exCreatedAt),
                $forum
            );
        }

        return $rawTopics;
    }

    private function filterFromNotTopics(Crawler $tableRow)
    {
        // This is ad, if no torrent size exists.
        $value = $tableRow->children()->getNode(2)->nodeValue;

        return null !== $this->sanitize($value);
    }

    private function collectRawTopic(Crawler $tableRow)
    {
        $tableRow = $tableRow->children();

        $exId           = $tableRow->getNode(0)->getAttribute('id');
        $rawTitle       = $tableRow->getNode(1)->nodeValue;
        $size           = $tableRow->getNode(2)->nodeValue;
        $exCreatedAt    = $tableRow->getNode(4)->nodeValue;

        return [ $exId, $rawTitle, $size, $exCreatedAt ];
    }

    private function sanitize($data)
    {
        if (is_string($data)) {
            $data = trim($data);
            $data = '' === $data ? null : $data;
        }

        return $data;
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

        return new RawForumDto(
            $this->sanitize($exId),
            $this->sanitize($title)
        );
    }

    public function parsePageState(string $content)
    {
        $crawler = new Crawler($content);

        $node = $crawler->filterXPath('//div[@id="pagination"]/p');
        $value = $node->getNode(0)->nodeValue;

        preg_match('~(\d+)\D+(\d+)~', $value, $matches);

        if (isset($matches[1]) and isset($matches[2])) {
            return [$matches[1], $matches[2]];
        }

        return [null, null];
    }
}
