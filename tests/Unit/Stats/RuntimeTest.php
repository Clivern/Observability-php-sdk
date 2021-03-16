<?php

declare(strict_types=1);

/*
 * This file is part of Symfony Observability SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Stats;

use PHPUnit\Framework\TestCase;
use Clivern\Observability\Stats\Runtime;

/**
 * Runtime Class Test.
 */
class RuntimeTest extends TestCase
{
    public function testType()
    {
        $runtime = new Runtime();
        $this->assertTrue($runtime instanceof Runtime);
    }
}
