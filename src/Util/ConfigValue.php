<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Util;

use Clivern\Metric\Contract\ConfigValueContract;

/**
 * Config Value Class.
 */
class ConfigValue implements ConfigValueContract
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Get Value.
     */
    public function value()
    {
        return $this->value;
    }
}
