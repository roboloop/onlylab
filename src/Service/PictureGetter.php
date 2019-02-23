<?php

namespace App\Service;

use App\Service\Parser\Html\PostHtmlParser;
use App\Url\Fastpic;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PictureGetter
{
    /** @var \App\Parser\Html\PostHtmlParser */
    private $htmlParser;

    /** @var \GuzzleHttp\Client */
    private $client;

    /** @var \App\Url\Fastpic */
    private $fastpic;

    public function __construct(PostHtmlParser $htmlParser, ContainerInterface $container, Fastpic $fastpic)
    {
        $this->htmlParser = $htmlParser;
        $this->client = $container->get('eight_points_guzzle.client.onlytracker');
        $this->fastpic = $fastpic;
    }

    /**
     * @param \App\Entity\Film[] $films
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPictures(array $films): array
    {
        foreach ($films as $film) {
            $url = $film->getHref();

            if ($body = $this->client->request('GET', $url)->getBody()) {
                $pics = $this->htmlParser->picUrls($body);
                $pics = $this->fastpic->realPathAll($pics);

                $film->setPics($pics);
            }
        }
        return $films;
    }
}
