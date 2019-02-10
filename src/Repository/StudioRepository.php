<?php

namespace App\Repository;

use App\Entity\Studio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Studio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studio[]    findAll()
 * @method Studio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Studio::class);
    }
}
