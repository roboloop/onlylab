<?php

namespace App\Controller;

use App\Dto\SearchOptions;
use App\Service\Search\SearchFlow;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{
    /**
     * @Route("/search", methods={"POST"}, name="search")
     *
     * @param \App\Dto\SearchOptions         $options
     * @param \App\Service\Search\SearchFlow $searchFlow
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(SearchOptions $options, SearchFlow $searchFlow): Response
    {


        return $this->json([]);
    }
}
