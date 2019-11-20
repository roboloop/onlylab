<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Service\GenreService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends BaseController
{
    /**
     * @Route("/genres", methods={"GET"}, name="genres")
     * @param \App\Service\GenreService $genreService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function genresWithTopics(GenreService $genreService)
    {
        $genres = $genreService->findAll();

        return $this->render('genre/index.html.twig', [
            'genres' => $genres
        ]);
    }

    /**
     * Get topics of this genre
     *
     * @Route("/genres/{genre}/topics", methods={"GET"}, name="genres_topics")
     * @ParamConverter("genre", options={
     *      "mapping": {"genre":"id"}
     * })
     *
     * @param \App\Entity\Genre                         $genre
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topics(Genre $genre)
    {
        // for ($i = 0; $i < 180; $i++) {
        //     $arr[] = rand(1,20);
        // }
        //
        // $result = $paginator->paginate($arr ?? [], 2, 25, [ 'distinct' => false]);

        $topics = $genre->getTopics();


        return $this->render('genre/topics.html.twig', [
            'genre'     => $genre,
            'topics'    => $topics,
        ]);
    }

    /**
     * Approve the genre
     *
     * @Route("/genres/{genre}/approve", methods={"POST"}, name="genres_approve")
     * @ParamConverter("genre", options={
     *      "mapping": {"genre":"id"}
     * })
     *
     * @param \App\Entity\Genre $genre
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approve(Genre $genre)
    {
        $genre->setIsApproved(true);

        $this->transaction($genre);

        return $this->redirectToRoute('genres_topics', ['genre' => $genre->getId()]);
    }

    /**
     * Reject the genre
     *
     * @Route("/genres/{genre}/reject", methods={"POST"}, name="genres_reject")
     * @ParamConverter("genre", options={
     *      "mapping": {"genre":"id"}
     * })
     *
     * @param \App\Entity\Genre $genre
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reject(Genre $genre)
    {
        $genre->setIsApproved(false);

        $this->transaction($genre);

        return $this->redirectToRoute('genres_topics', ['genre' => $genre->getId()]);
    }
}
