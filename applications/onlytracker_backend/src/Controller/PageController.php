<?php

namespace OnlyTracker\BackEnd\Controller;

use Symfony\Component\Routing\Annotation\Route;

class PageController extends BaseController
{
    /**
     * @Route("/", methods={"GET"}, name="page_main")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function page()
    {
        return $this->render('page/main.html.twig');
    }
}
