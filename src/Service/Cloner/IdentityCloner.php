<?php

namespace App\Service\Cloner;

use Doctrine\ORM\EntityManagerInterface;

class IdentityCloner
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Clone id from source to dist.
     *
     * @param $source
     * @param $dist
     *
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function cloneId($source, $dist)
    {
        $sourceMeta = $this->em->getClassMetadata(get_class($source));
        $distMeta   = $this->em->getClassMetadata(get_class($dist));

        if ($sourceMeta->rootEntityName !== $distMeta->rootEntityName) {
            throw new \LogicException('Source and dist entities must be same instance.');
        }

        $meta = $sourceMeta;

        $identityName = $meta->getSingleIdentifierColumnName();

        $clonedId = $meta->getReflectionProperty($identityName)->getValue($source);

        $sourceMeta->getReflectionProperty($identityName)->setValue($dist, $clonedId);
    }
}
