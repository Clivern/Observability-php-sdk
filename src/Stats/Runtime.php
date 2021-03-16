<?php

declare(strict_types=1);

/*
 * This file is part of Observability PHP SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Stats;

/**
 * Runtime Class.
 */
class Runtime
{
    /**
     * Gets the current PHP version
     */
    public static function phpVersion(): string
    {
        return phpversion();
    }

    /**
     * Gets the current resource usages
     */
    public static function getResourceUsages(): ?array
    {
        return getrusage();
    }

    /**
     * Returns active resources
     */
    public static function getResources(): ?array
    {
        return get_resources();
    }

    /**
     * Reclaims memory used by the Zend Engine memory manager
     */
    public static function gcMemCaches(): int
    {
        return gc_mem_caches();
    }

    /**
     * Gets the version of the current Zend engine
     */
    public static function getZendVersion(): string
    {
        return zend_version();
    }

    /**
     * Get the amount of memory allocated to PHP
     *
     * @param  bool|boolean $real_usage
     * @return int
     */
    public static function getMemoryUsage(bool $real_usage = false): int
    {
        return memory_get_usage($real_usage);
    }

    /**
     * Get the peak of memory allocated by PHP
     *
     * @param  bool|boolean $real_usage
     * @return int
     */
    public static function getMemoryPeakUsage(bool $real_usage = false): int
    {
        return memory_get_peak_usage($real_usage);
    }
}
