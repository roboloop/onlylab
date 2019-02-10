<?php

namespace App\Bundle;

use App\DependencyInjection\ApplicationExtension;
use App\DependencyInjection\CompilerPass\ParserCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ParserCompilerPass());
    }

    public function getContainerExtension()
    {
        return new ApplicationExtension();
    }
}
