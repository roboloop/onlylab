<?php

namespace OnlyTracker\BackEnd\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PageController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('js.html.twig');
    }
}
