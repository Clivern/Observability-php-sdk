<?php

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Util;

use Clivern\Metric\Util\Config;
use PHPUnit\Framework\TestCase;

/**
 * Config Class Test.
 */
class ConfigTest extends TestCase
{
    /**
     * Test Case.
     */
    public function testCase()
    {
        $this->assertTrue((new Config()) instanceof Config);
    }
}
