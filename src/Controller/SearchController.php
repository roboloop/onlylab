<?php

namespace App\Controller;

use App\HtmlParser\ForumHtmlParser;
use App\HtmlParser\PostHtmlParser;
use App\Service\Filter;
use App\Service\PictureGetter;
use App\Url\Fastpic;
use App\Util\UrlResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="app_index")
     *
     * @param \App\HtmlParser\ForumHtmlParser $parser
     * @param \App\Service\Filter             $filter
     * @param \App\Util\UrlResolver           $urlResolver
     * @param \App\Service\PictureGetter      $pictureGetter
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(ForumHtmlParser $parser, Filter $filter, UrlResolver $urlResolver, PictureGetter $pictureGetter)
    {
        $path = $this->getParameter('kernel.project_dir');

        $content = file_get_contents($path . '/examples/siterip.html');

        $filtered   = $parser->getParsed($content);
        $filtered   = $filter->getFiltered($filtered);
        $filtered   = $pictureGetter->getPictures($filtered);

        return $this->render('search/index.html.twig', [
            'urlResolver'   => $urlResolver,
            'films'         => $filtered,
        ]);
    }

    /**
     * @Route("/1", methods={"GET"}, name="app_show")
     *
     * @param \App\HtmlParser\PostHtmlParser $parser
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(PostHtmlParser $parser, Fastpic $fastpic)
    {
        $path = $this->getParameter('kernel.project_dir');

        $content = file_get_contents($path . '/examples/post.html');

        $res = $parser->picUrls($content);

        $urls = $fastpic->realPathAll($res);

        return $this->render('search/show.html.twig', [
            'urls'  => $urls,
        ]);
    }
}
