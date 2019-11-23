<?php

namespace OnlyTracker\BackEnd\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
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
