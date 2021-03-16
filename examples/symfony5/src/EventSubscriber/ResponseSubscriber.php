<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Clivern\Observability\Aggregation\MemcachedAggregate;

class ResponseSubscriber implements EventSubscriberInterface
{
    /** @var MemcachedAggregate */
    private $metricsClient;

    public function __construct(
        MemcachedAggregate $metricsClient
    ) {
        $this->metricsClient = $metricsClient;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
