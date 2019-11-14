<?php

namespace App\Service\Parser\Html;

use App\Constant\ImageType;
use App\Dto\ImageDto;
use App\Dto\RawTopicDto;
use App\Domain\Service\SpoilerIdentifier;
use Closure;
use Symfony\Component\DomCrawler\Crawler;

class TopicHtmlParser
{
    private $spoilerIdentifier;

    public function __construct(SpoilerIdentifier $spoilerIdentifier)
    {
        $this->spoilerIdentifier = $spoilerIdentifier;
    }

    public function rawTopicDto(string $content)
    {
        $rawTitle   = $this->rawTitle($content);
        $trackerId      = $this->trackerId($content);
        $size       = $this->size($content);
        $imagesDto  = $this->rawImagesDto($content);
        $forumId    = $this->forumId($content);
        $forumTitle = $this->forumTitle($content);

        return (new RawTopicDto)
            ->setTrackerId($trackerId)
            ->setRawTitle($rawTitle)
            ->setSize($size)
            ->addImages($imagesDto)
            ->setForumId($forumId)
            ->setForumTitle($forumTitle);
    }

    public function forumId(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//div[@id="main_content_wrap"]/table/tr/td/table/tr/td/a');
        $href       = $body->last()->getNode(0)->getAttribute('href');

        preg_match('~f=(\d+)~', $href, $matches);

        return isset($matches[1]) ? (int) $matches[1] : null;
    }

    public function forumTitle(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//div[@id="main_content_wrap"]/table/tr/td/table/tr/td/a');
        $text       = $body->last()->getNode(0)->nodeValue;

        return $text;
    }

    public function rawTitle(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        $rawTitle   = $body->getNode(0)->nodeValue;

        return $rawTitle;
    }

    public function trackerId(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table//h1[@class="maintitle"]/a');
        preg_match('~t=(\d+)~', $body->getNode(0)->getAttribute('href'), $matches);
        $trackerId      = (int) $matches[1];

        return $trackerId;
    }

    public function size(string $content)
    {
        $crawler    = new Crawler($content);
        $body       = $crawler->filterXPath('//table[@class="attach bordered med"]/tr');
        $size       = $body->getNode(4)->lastChild->nodeValue ?? null;

        return $size;
    }

    public function rawImagesDto(string $content)
    {
        $crawler = new Crawler($content);
        $body = $crawler->filterXPath(
            // Post number
            // (since each message is a post, the header of the post has a number. 1 ----|
            '//div[@id="page_content"]//table[@class="topic"]//tbody[contains(@id, "post_")][1]//div[@class="post_body"]'
        );

        // Images are from under spoilers
        $imagesDto[] = $this->underSpoiler($body);

        // Images are posters
        $imagesDto[] = $this->posters($body);

        return array_merge(...$imagesDto);
    }

    protected function underSpoiler(Crawler $body)
    {
        // Get all the spoilers
        $spoilers   = $body->filterXPath('//div[@class="sp-wrap"]');
        $count      = $spoilers->count();

        // Iterate over all spoilers
        for ($i = 0; $i < $count; $i++) {
            $node = $spoilers->eq($i);
            $header = $node->first()->getNode(0)->nodeValue;
            $vars = $node->filterXPath('//var[@class="postImg"]');

            $imagesDto[] = $this->setType(
                $vars->each(Closure::fromCallable([$this, 'rawImageDto'])),
                $this->spoilerIdentifier->identifyType($header)
            );
        }

        // To plain array
        return array_merge(...$imagesDto ?? [[]]);
    }

    protected function posters(Crawler $body)
    {
        // Get all images
        $crawlerImages = $body->filterXPath('//var[@class="postImg"]');

        // Everything that is not under the spoiler is a poster
        // Banner filtering will be done later
        $filtered = $crawlerImages->reduce(Closure::fromCallable([$this, 'excludeUnderSpoiler']));

        return $this->setType(
            $filtered->each(Closure::fromCallable([$this, 'rawImageDto'])),
            ImageType::POSTER
        );
    }

    protected function rawImageDto(Crawler $imageCrawler)
    {
        // Getting the closest top parent. Taking a reference to the original.
        $urlToOriginal = $imageCrawler->parents()->getNode(0)->getAttribute('href');
        // Getting a direct link to the preview
        $directUrlToPreview = $imageCrawler->getNode(0)->getAttribute('title');

        // If there is no link to the original, then the preview is the original.
        if ('' === $urlToOriginal) {
            $directUrlToOriginal = $directUrlToPreview;
        }

        // Convert all empty strings to NULL
        $urlToOriginal          = empty($urlToOriginal) ? null : $urlToOriginal;
        $directUrlToOriginal    = empty($directUrlToOriginal) ? null : $directUrlToOriginal;
        $directUrlToPreview     = empty($directUrlToPreview) ? null : $directUrlToPreview;

        return (new ImageDto())
            ->setUrlOriginal($urlToOriginal)
            ->setDirectUrlOriginal($directUrlToOriginal)
            ->setDirectUrlPreview($directUrlToPreview);
    }

    /**
     * Add an image type to an image group
     *
     * @param ImageDto[] $imagesDto
     * @param int        $type
     *
     * @return \App\Dto\ImageDto[]|array
     */
    private function setType(array $imagesDto, int $type)
    {
        array_walk($imagesDto, function (&$imageDto) use ($type) {
            /** @var $imageDto ImageDto */
            $imageDto->setType($type);
        });

        return $imagesDto;
    }

    /**
     * Exclude all images below the spoiler.
     *
     * @param \Symfony\Component\DomCrawler\Crawler $node
     *
     * @return bool
     */
    private function excludeUnderSpoiler(Crawler $node)
    {
        $parents = $node->parents();
        $count = $parents->count();
        $include = true;
        for ($i = 0; $i < $count; $i++) {
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
}
