<?php

declare(strict_types=1);

/*
 * This file is part of Symfony Observability SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Stats;

/**
 * Execution Class.
 */
class Execution
{
    /**
     * @var integer
     */
    private $start = 0;

    /**
     * @var integer
     */
    private $end = 0;

    /**
     * Track start time
     */
    public function start(): void
    {
        $this->start = microtime(true);
    }

    /**
     * Track end time
     */
    public function end(): void
    {
        $this->end = microtime(true);
    }

    /**
     * Get Execution Time in Seconds
     *
     * @return int
     */
    public function getTimeInSeconds(): float
    {
        return $this->end - $this->start;
    }

    /**
     * Get Execution Time in Minutes
     *
     * @return float
     */
    public function getTimeInMinutes(): float
    {
        return ($this->end - $this->start) / 60;
    }
}
