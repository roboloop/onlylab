<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Infrastructure\Util\Hydrator;

use Doctrine\Instantiator\Instantiator;
use GeneratedHydrator\Configuration;

class Hydrator implements HydratorInterface
{
    /**
     * {@inheritDoc}
     * @throws \Doctrine\Instantiator\Exception\ExceptionInterface
     */
    public function hydrate(array $data, string $className)
    {
        // TODO: cache to improve performance (I/O issue)

        $instantiator   = new Instantiator();
        $dto            = $instantiator->instantiate($className);

        $config         = new Configuration($className);
        $hydratorClass  = $config->createFactory()->getHydratorClass();
        $hydrator       = new $hydratorClass();

        /** @var $hydrator \Zend\Hydrator\HydratorInterface */
        $hydrator->hydrate(
            $data,
            $dto
        );

        return $dto;
    }
}
