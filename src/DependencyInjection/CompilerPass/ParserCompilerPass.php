<?php

namespace App\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ParserCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition     = $container->getDefinition('App\Loader\ParsersLoader');
        $taggedServices = $container->findTaggedServiceIds('app.contract.parser.parser');

        $parsers = [];
        foreach ($taggedServices as $serviceId => $tags) {
            $parsers[] = new Reference($serviceId);
        }

        $definition->addMethodCall('addParsers', [$parsers]);
    }
}
