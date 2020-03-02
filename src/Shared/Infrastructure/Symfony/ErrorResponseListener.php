<?php

declare (strict_types=1);

namespace OnlyTracker\Shared\Infrastructure\Symfony;

use OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\ErrorValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorResponseListener implements EventSubscriberInterface
{
    public function handle(ExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $e = $event->getThrowable();

        if ($e instanceof ErrorValidationException) {
            if ($event->getRequest()->headers->get('content-type') === 'application/json') {
                $event->setResponse(
                    new JsonResponse(['errors' => $e->getMessage()])
                );
            } else {
                $event->setResponse(
                    new Response($e->getMessage())
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'handle',
        ];
    }
}
