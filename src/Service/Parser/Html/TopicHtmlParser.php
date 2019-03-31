<?php

namespace App\Service\Parser\Html;

use App\Constant\ImageType;
use App\Dto\ImageDto;
use App\Service\Identifier\NameSpoilerIdentifier;
use Closure;
use Symfony\Component\DomCrawler\Crawler;

class TopicHtmlParser
{
    private $spoilerIdentifier;

    public function __construct(NameSpoilerIdentifier $spoilerIdentifier)
    {
        $this->spoilerIdentifier = $spoilerIdentifier;
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

        // If there is no link to the original, then the preview is the original
        if ('' === $urlToOriginal) {
            $directUrlToOriginal = $directUrlToPreview;
        }

        return (new ImageDto())
            ->setUrlOriginal($urlToOriginal)
            ->setDirectUrlOriginal($directUrlToOriginal ?? null)
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
