<?php

namespace App\Controller;

use App\Client\TrackerClient;
use App\Service\Worker\ForumPageWorker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     *
     * @param \App\Client\TrackerClient               $client
     * @param \App\Service\Worker\ForumPageWorker $forumPageWorker
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function page(TrackerClient $client, ForumPageWorker $forumPageWorker)
    {
        // $response = $client->get('/forum/viewforum.php?f=7133');
        // $content = $response->getBody()->getContents();

        $content = file_get_contents('../examples/forum_page.html');

        $result = $forumPageWorker->work($content);

        return $this->json(['Good']);
    }
}
