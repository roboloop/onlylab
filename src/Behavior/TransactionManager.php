<?php

namespace App\Behavior;

use Doctrine\ORM\EntityManagerInterface;

class TransactionManager
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transaction(): void
    {
        $objects = func_get_args();

        array_walk_recursive($objects, function ($object) {
            if (null === $object) {
                return;
            }

            $this->em->contains($object)
                ? $this->em->merge($object)
                : $this->em->persist($object);
        });

        $this->em->flush();
    }

    public function remove(): void
    {
        $objects = func_get_args();

        array_walk_recursive($objects, function ($object) {
            if (null === $object) {
                return;
            }

            $this->em->remove($object);
        });

        $this->em->flush();
    }
}
