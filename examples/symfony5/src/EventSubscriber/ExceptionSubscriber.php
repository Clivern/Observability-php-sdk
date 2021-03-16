<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Clivern\Observability\Aggregation\MemcachedAggregate;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /** @var MemcachedAggregate */
    private $metricsClient;

    public function __construct(
        MemcachedAggregate $metricsClient
    ) {
        $this->metricsClient = $metricsClient;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
