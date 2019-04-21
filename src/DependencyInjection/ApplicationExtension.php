<?php

namespace App\DependencyInjection;

use App\Contract\Parser\ParserInterface;
use App\Contract\Service\UrlConverter\UrlConverterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ApplicationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(ParserInterface::class)
            ->addTag('app.contract.parser.parser');

        $container->registerForAutoconfiguration(UrlConverterInterface::class)
            ->addTag('app.converter');
    }
}
