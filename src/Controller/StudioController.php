<?php

namespace App\Controller;

use App\Constant\StudioStatus;
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

    /**
     * Approve the studio
     *
     * @Route("/studios/{studio}/approve", methods={"POST"}, name="studios_approve")
     * @ParamConverter("studio", options={
     *      "mapping": {"studio":"id"}
     * })
     *
     * @param \App\Entity\Studio $studio
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approve(Studio $studio)
    {
        $studio->setIsApproved(true);

        $this->transaction($studio);

        return $this->redirectToRoute('studios_topics', ['studio' => $studio->getId()]);
    }

    /**
     * Reject the studio
     *
     * @Route("/studios/{studio}/reject", methods={"POST"}, name="studios_reject")
     * @ParamConverter("studio", options={
     *      "mapping": {"studio":"id"}
     * })
     *
     * @param \App\Entity\Studio $studio
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reject(Studio $studio)
    {
        $studio->setIsApproved(false);

        $this->transaction($studio);

        return $this->redirectToRoute('studios_topics', ['studio' => $studio->getId()]);
    }

    /**
     * Ban the studio
     *
     * @Route("/studios/{studio}/ban", methods={"POST"}, name="studios_ban")
     * @ParamConverter("studio", options={
     *      "mapping": {"studio":"id"}
     * })
     *
     * @param \App\Entity\Studio $studio
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ban(Studio $studio)
    {
        $studio->setStatus(StudioStatus::BANNED);

        $this->transaction($studio);

        return $this->redirectToRoute('studios_topics', ['studio' => $studio->getId()]);
    }

    /**
     * Unban the studio
     *
     * @Route("/studios/{studio}/unban", methods={"POST"}, name="studios_unban")
     * @ParamConverter("studio", options={
     *      "mapping": {"studio":"id"}
     * })
     *
     * @param \App\Entity\Studio $studio
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unban(Studio $studio)
    {
        $studio->setStatus(StudioStatus::TYPICAL);

        $this->transaction($studio);

        return $this->redirectToRoute('studios_topics', ['studio' => $studio->getId()]);
    }
}
