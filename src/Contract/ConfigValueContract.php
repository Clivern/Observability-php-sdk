<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Contract;

/**
 * ConfigValue Contract.
 */
interface ConfigValueContract
{
    /**
     * Get Value.
     *
     * @return mixed
     */
    public function value();
}
