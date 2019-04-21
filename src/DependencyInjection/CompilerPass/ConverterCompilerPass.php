<?php

namespace App\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConverterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('app.converter');

        $converters = [];
        foreach ($taggedServices as $serviceId => $tags) {
            $converters[] = new Reference($serviceId);
        }

        $converterDefinition = $container->getDefinition('App\Service\UrlConverter\ImageUrlConverter');
        $converterDefinition->setArgument(0, $converters);
    }
}
