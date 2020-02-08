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
     * Store the value.
     *
     * @param mixed $value
     */
    public function persist(array $value): bool;
}
