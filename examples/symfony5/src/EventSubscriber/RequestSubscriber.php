<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Clivern\Observability\Aggregation\MemcachedAggregate;

class RequestSubscriber implements EventSubscriberInterface
{
    /** @var MemcachedAggregate */
    private $metricsClient;

    public function __construct(
        MemcachedAggregate $metricsClient
    ) {
        $this->metricsClient = $metricsClient;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        try {
            $this->metricsClient->report([[
                'key' => 'orders_service.metrics.total_http_calls',
                'value' => 1,
                'time' => time(),
                'aggregateFunc' => MemcachedAggregate::SUM_AGGREGATE_FUNCTION
            ]]);
        } catch (\Exception $e) {
            // Do something about it
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
