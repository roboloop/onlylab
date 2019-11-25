<?php

namespace OnlyTracker\BackEnd\Controller\Web;

use OnlyTracker\Domain\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="page_main")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke()
    {

        return $this->render('page/main.html.twig');
        // return $this->render('base.html.twig');
    }
}
