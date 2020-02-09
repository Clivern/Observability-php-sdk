<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Contract;

/**
 * Storage Driver Contract.
 */
interface StorageDriverContract
{
    /**
     * Establish a connection.
     */
    public function connect(): bool;

    /**
     * Reconnect.
     */
    public function reconnect(): bool;

    /**
     * Store a set of values.
     */
    public function persist(array $values): bool;

    /**
     * Close Connection.
     */
    public function close(): bool;
}
