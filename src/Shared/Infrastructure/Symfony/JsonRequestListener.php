<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Infrastructure\Symfony;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class JsonRequestListener implements EventSubscriberInterface
{
    public function jsonToArray(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ('POST' !== $request->getMethod() || 'json' !== $request->getContentType() || !is_string($request->getContent())) {
            return;
        }

        $data = json_decode($request->getContent(), true, 8);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException('Invalid json content body: ' . json_last_error_msg());
        }

        $request->request->replace(is_array($data) ? $data : []);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['jsonToArray', 100],
        ];
    }
}
