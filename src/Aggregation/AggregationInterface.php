<?php

declare(strict_types=1);

/*
 * This file is part of Symfony Observability SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Aggregation;

/**
 * Aggregation Interface.
 */
interface AggregationInterface
{
    /**
     * Report Metrics.
     */
    public function report(array $metrics): void;

    /**
     * Flush Metrics.
     */
    public function flush(): array;
}
