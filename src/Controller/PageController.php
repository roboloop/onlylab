<?php

namespace App\Controller;

use App\Client\TrackerClient;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Service\Collection\GenreCollection;
use App\Service\Handler\ForumPageHandler;
use App\Service\Handler\TopicPageHandler;
use App\Service\Script\CombForum;
use App\Service\Script\CombTopic;
use App\Service\UrlConverter\ImageUrlConverter;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Andx;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends BaseController
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
    public function page(CombForum $combForum, CombTopic $combTopic)
    {
        $combForum->execute(2007);
        $combTopic->execute(2505688);

        // $response = $client->get('/forum/viewforum.php?f=7133');
        // $content = $response->getBody()->getContents();

        // $content = file_get_contents('../examples/forum_page.html');
        // $content = file_get_contents('../examples/topic_page.html');
        // $content = file_get_contents('../examples/topic_page.html');
        // $content = file_get_contents('../examples/dum.html');

        // $result = $forumPageWorker->pageIs($content);
        // $result = $forumPageWorker->handleAuth($content);

        // $this->transaction($result);
        // $result = $topicPageWorker->handleAuth($content);

        return $this->json(['Good']);
    }
}
