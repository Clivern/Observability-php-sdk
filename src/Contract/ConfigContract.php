<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Contract;

/**
 * Config Contract.
 */
interface ConfigContract
{
    /**
     * Set Config Item.
     */
    public function set(string $key, ConfigValueContract $value);

    /**
     * Get Config Item.
     */
    public function get(string $key, ConfigValueContract $default): ConfigValueContract;

    /**
     * Check if Item Exists.
     */
    public function exists(string $key): bool;
}
