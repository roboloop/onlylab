<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Studio;
use App\Service\GenreService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudioController extends BaseController
{
    /**
     * Get topics of this studio
     *
     * @Route("/studios/{studio}/topics", methods={"GET"}, name="studios_topics")
     * @ParamConverter("studio", options={
     *      "mapping": {"studio":"id"}
     * })
     *
     * @param \App\Entity\Studio                        $studio
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topics(Studio $studio, Request $request, PaginatorInterface $paginator)
    {
        $topics = $studio->getTopics();

        return $this->render('studio/topics.html.twig', [
            'studio'    => $studio,
            'topics'    => $topics,
        ]);
    }
}
