<?php

namespace App\Controller;

use App\Client\TrackerClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     *
     * @param \App\Client\TrackerClient $client
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function page(TrackerClient $client)
    {
        $response = $client->get('/forum/viewforum.php?f=7133');
        $content = $response->getBody()->getContents();
        $transformed = iconv('windows-1251', 'UTF-8', $content);

        return $this->json(['Good']);
    }
}
