<?php

namespace OnlyTracker\BackEnd\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('js.html.twig');
    }
}
