<?php

namespace OnlyTracker\BackEnd\Controller\Web;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use OnlyTracker\Domain\Entity\Genre;
use OnlyTracker\Domain\Identity\GenreId;
use OnlyTracker\Infrastructure\Doctrine\Types\GenreIdType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    public function __invoke()
    {
        $em = $this->getDoctrine()->getManager();
        $r = $this->getDoctrine()->getRepository(Genre::class);
        // $res = $r->findBy(['id' => 1]);
        $res = $r->findBy(['id' => '623ef5a7-c94f-40db-9146-d244b47e2d8c']);

        $res = $em
            ->createQuery('SELECT g FROM OnlyTracker\Domain\Entity\Genre g WHERE g.id = :id')
            // ->execute(['id' => new GenreId('623ef5a7-c94f-40db-9146-d244b47e2d8c')]);
            ->execute(new ArrayCollection([new Parameter('id', new GenreId('623ef5a7-c94f-40db-9146-d244b47e2d8c'), GenreIdType::NAME)]));

        return $this->render('page/main.html.twig');
    }
}
