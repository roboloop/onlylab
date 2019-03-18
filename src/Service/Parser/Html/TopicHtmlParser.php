<?php

namespace App\Service\Parser\Html;

use App\Constant\ImageType;
use App\Dto\ImageDto;
use App\Service\Identifier\NameSpoilerIdentifier;
use Symfony\Component\DomCrawler\Crawler;

class TopicHtmlParser
{
    private $spoilerIdentifier;

    public function __construct(NameSpoilerIdentifier $spoilerIdentifier)
    {
        $this->spoilerIdentifier = $spoilerIdentifier;
    }

    public function rawTopic(string $content)
    {
        $crawler = new Crawler($content);
        $body = $crawler->filterXPath(
            // Post number
            // (since each message is a post, the header of the post has a number. 1 ----|
            '//div[@id="page_content"]//table[@class="topic"]//tbody[contains(@id, "post_")][1]//div[@class="post_body"]'
        );

        // Getting spoilers
        $spoilers = $body->filterXPath('//div[@class="sp-wrap"]');

        $count = $spoilers->count();
        for ($i = 0; $i < $count; $i++) {
            $node = $spoilers->eq($i);
            $header = $node->first()->getNode(0)->nodeValue;
            $vars = $node->filterXPath('//var[@class="postImg"]');

            if ($this->spoilerIdentifier->isScreenshots($header)) {
                $type = ImageType::SCREENSHOT;
            } elseif ($this->spoilerIdentifier->isScreenListing($header)) {
                $type = ImageType::SCREENLISTING;
            } elseif ($this->spoilerIdentifier->isGif($header)) {
                $type = ImageType::GIF;
            } else {
                $type = ImageType::OTHER;
            }

            foreach ($vars as $var) {
                $href = $var->parentNode->getAttribute('href');
                if (empty($href)) {
                    // TODO: If there is no original, then the preview is most likely the original
                }
                $underSpoilers[] = $var->getAttribute('title');
                $urls[] = (new ImageDto())
                    ->setUrlOriginal($href)
                    ->setUrlPreview($var->getAttribute('title'))
                    ->setType($type);
            }
        }

        // Getting the posters (All images - (minus) all those under the spoiler). No shit
        foreach ($body->filterXPath('//var[@class="postImg"]') as $image) {
            $images[] = $image->getAttribute('title');
        }

        $posters = array_diff($images ?? [], $underSpoilers ?? []);
        foreach ($posters as $poster) {
            $urls[] = (new ImageDto())
                ->setUrlPreview($poster)
                ->setType(ImageType::POSTER);
        }

        return $urls ?? [];
    }
}
