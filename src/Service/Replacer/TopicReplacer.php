<?php

namespace App\Service\Replacer;

use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionProperty;

/**
 * Class TopicReplacer
 *
 * @deprecated Never use!
 */
class TopicReplacer
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function replace(Topic $original, Topic $donor)
    {
        $classMeta = $this->em->getClassMetadata(Topic::class);
        $reflProperties = $classMeta->getReflectionProperties();
        $idNameProperties = array_flip($classMeta->getIdentifier());
        $reflPropertiesWithoutId = array_diff_key($reflProperties, $idNameProperties);
        $assocNameProperties = $classMeta->getAssociationMappings();

        $reflOnlyProperties = array_diff_key($reflPropertiesWithoutId, $assocNameProperties);
        $reflOnlyAssoc = array_intersect_key($reflPropertiesWithoutId, $assocNameProperties);

        $assocNames = $classMeta->getAssociationNames();
        foreach ($assocNames as $assocName) {
            if ($classMeta->isCollectionValuedAssociation($assocName)) {
                $reflProperty = $classMeta->getReflectionProperty($assocName);
                $originalCollection = $reflProperty->getValue($original);
                $originalCollection->clear();
                $donorCollection = $reflProperty->getValue($donor);
                foreach ($donorCollection as $donorItem) {
                    $originalCollection->add($donorItem);
                }
                $donorCollection->clear();
            }
        }
        $classMeta->isCollectionValuedAssociation('');

        foreach ($reflOnlyProperties as $property) {
            /** @var $property ReflectionProperty */
            $value = $property->getValue($donor);
            $property->setValue($original, $value);
        }
    }
}
