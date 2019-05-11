<?php

namespace App\Controller;

use App\Behavior\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected function transaction(): void
    {
        if (!$this->has('app.util.transaction_manager')) {
            throw new \LogicException('No transaction manager');
        }

        $this->container->get('app.util.transaction_manager')->transaction(...func_get_args());
    }

    protected function getEm(): EntityManagerInterface
    {
        if (!$this->has('doctrine.orm.entity_manager')) {
            throw new \LogicException('No default entity manager');
        }

        return $this->container->get('doctrine.orm.entity_manager');
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'app.util.transaction_manager'  => '?'.TransactionManager::class,
            'doctrine.orm.entity_manager'   => '?'.EntityManagerInterface::class,
        ]);
    }
}
