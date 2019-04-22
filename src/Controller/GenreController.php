<?php

namespace App\Controller;

use App\Service\GenreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genres", methods={"GET"})
     * @param \App\Service\GenreService $genreService
     */
    public function genresWithTopics(GenreService $genreService)
    {
        $genres = $genreService->genresWithTopics();
        // $genres = $genreService->findAll();

        return $this->render('genre/index.html.twig', [
            'genres' => $genres
        ]);
    }
}
