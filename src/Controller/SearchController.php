<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController
{
    /**
     * @Route("/", methods={"GET"}, name="app_index")
     */
    public function index()
    {
        return new Response('just text');
    }
}
