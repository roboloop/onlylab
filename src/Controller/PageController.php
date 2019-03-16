<?php

namespace App\Controller;

use App\Client\TrackerClient;
use App\Service\Handler\ForumPageHandler;
use App\Service\Handler\TopicPageHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     *
     * @param \App\Client\TrackerClient                 $client
     * @param \App\Service\Handler\ForumPageHandler $forumPageWorker
     * @param \App\Service\Handler\TopicPageHandler $topicPageWorker
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function page(TrackerClient $client, ForumPageHandler $forumPageWorker, TopicPageHandler $topicPageWorker)
    {
        // $response = $client->get('/forum/viewforum.php?f=7133');
        // $content = $response->getBody()->getContents();

        $content = file_get_contents('../examples/forum_page.html');
        // $content = file_get_contents('../examples/topic_page.html');

        $result = $forumPageWorker->handleAuth($content);
        // $result = $topicPageWorker->work($content);

        return $this->json(['Good']);
    }
}
