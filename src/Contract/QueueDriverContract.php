<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Contract;

/**
 * Queue Driver Contract.
 */
interface QueueDriverContract
{
    /**
     * Push value into queue.
     *
     * @param mixed $value
     */
    public function push(array $value): bool;

    /**
     * Removes and returns the value at the front of the queue.
     */
    public function pop(int $size = 1): array;

    /**
     * Get size of the queue.
     */
    public function size(): int;

    /**
     * Cleanup the queue.
     */
    public function clean(): bool;

    /**
     * Check if queue is empty.
     */
    public function isEmpty(): bool;
}
