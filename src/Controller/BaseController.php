<?php

namespace App\Controller;

use App\Behavior\TransactionManager;
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

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'app.util.transaction_manager'  => '?'.TransactionManager::class,
        ]);
    }
}
