<?php

namespace App\Bundle;

use App\DependencyInjection\ApplicationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    }

    public function getContainerExtension()
    {
        return new ApplicationExtension();
    }
}
