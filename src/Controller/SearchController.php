<?php

namespace App\Controller;

use App\Service\Parser\Html\ForumHtmlParser;
use App\Service\Parser\Html\TopicHtmlParser;
use App\Service\Filter;
use App\Service\PictureGetter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    // /**
    //  * @Route("/", methods={"GET"}, name="app_index")
    //  *
    //  * @param \App\Parser\Html\ForumHtmlParser $parser
    //  * @param \App\Service\Filter             $filter
    //  * @param \App\Util\UrlResolver           $urlResolver
    //  * @param \App\Service\PictureGetter      $pictureGetter
    //  *
    //  * @return \Symfony\Component\HttpFoundation\Response
    //  * @throws \GuzzleHttp\Exception\GuzzleException
    //  */
    // public function index(ForumHtmlParser $parser, Filter $filter, UrlResolver $urlResolver, PictureGetter $pictureGetter)
    // {
    //     $path = $this->getParameter('kernel.project_dir');
    //
    //     $content = file_get_contents($path . '/examples/siterip.html');
    //
    //     $filtered   = $parJoinColumnser->getParsed($content);
    //     $filtered   = $filter->getFiltered($filtered);
    //     $filtered   = $pictureGetter->getPictures($filtered);
    //
    //     return $this->render('search/index.html.twig', [
    //         'urlResolver'   => $urlResolver,
    //         'films'         => $filtered,
    //     ]);
    // }
    //
    // /**
    //  * @Route("/1", methods={"GET"}, name="app_show")
    //  *
    //  * @param \App\Parser\Html\TopicHtmlParser $parser
    //  *
    //  * @return \Symfony\Component\HttpFoundation\Response
    //  */
    // public function show(TopicHtmlParser $parser, Fastpic $fastpic)
    // {
    //     $path = $this->getParameter('kernel.project_dir');
    //
    //     $content = file_get_contents($path . '/examples/post.html');
    //
    //     $res = $parser->picUrls($content);
    //
    //     $urls = $fastpic->realPathAll($res);
    //
    //     return $this->render('search/show.html.twig', [
    //         'urls'  => $urls,
    //     ]);
    // }
}
