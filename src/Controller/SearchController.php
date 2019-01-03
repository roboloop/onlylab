<?php

namespace App\Controller;

use App\Service\Filter;
use App\Service\ForumParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="app_index")
     *
     * @param \App\Service\ForumParser $parser
     * @param \App\Service\Filter      $filter
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ForumParser $parser, Filter $filter)
    {
        $path = $this->getParameter('kernel.project_dir');

        $content = file_get_contents($path . '/examples/siterip.html');

        $parsed     = $parser->getParsed($content);
        $filtered   = $filter->getFiltered($parsed);

        return $this->render('search/index.html.twig', [
            'films' => $filtered,
            'controller_name' => get_class($this),
        ]);
    }
}
