<?php

declare(strict_types=1);

/*
 * This file is part of Observability PHP SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Stats;

/**
 * Execution Class.
 */
final class Execution
{
    /**
     * @var int
     */
    private $start = 0;

    /**
     * @var int
     */
    private $end = 0;

    /**
     * Track start time.
     */
    public function start(): void
    {
        $this->start = microtime(true);
    }

    /**
     * Track end time.
     */
    public function end(): void
    {
        $this->end = microtime(true);
    }

    /**
     * Get Execution Time in Seconds.
     *
     * @return int
     */
    public function getTimeInSeconds(): float
    {
        return $this->end - $this->start;
    }

    /**
     * Get Execution Time in Minutes.
     */
    public function getTimeInMinutes(): float
    {
        return ($this->end - $this->start) / 60;
    }
}
