<?php

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Util;

use Clivern\Metric\Util\Config;
use Clivern\Metric\Util\ConfigValue;
use PHPUnit\Framework\TestCase;

/**
 * Config Class Test.
 */
class ConfigTest extends TestCase
{
    public function testConfig()
    {
        $config = new Config();
        $this->assertTrue($config instanceof Config);
        $config->set('key', new ConfigValue('value'));
        $this->assertSame('value', $config->get('key', new ConfigValue())->value());
        $this->assertTrue($config->exists('key'));
        $this->assertFalse($config->exists('not_found'));
    }

    public function testConfigValue()
    {
        $configValue = new ConfigValue('value');
        $this->assertTrue($configValue instanceof ConfigValue);
        $this->assertSame('value', $configValue->value());
    }
}
