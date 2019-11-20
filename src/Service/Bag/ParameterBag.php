<?php

namespace App\Service\Bag;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ParameterBag
{
    private $baseUrl;

    public function __construct(ContainerInterface $container)
    {
        // $this->baseUrl = $container->getParameter('base_url');
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
