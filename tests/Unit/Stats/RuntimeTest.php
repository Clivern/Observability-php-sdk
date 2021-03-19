<?php

declare(strict_types=1);

/*
 * This file is part of Observability PHP SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Stats;

use Clivern\Observability\Stats\Runtime;
use PHPUnit\Framework\TestCase;

/**
 * Runtime Class Test.
 */
class RuntimeTest extends TestCase
{
    public function testType()
    {
        $runtime = new Runtime();
        self::assertTrue($runtime instanceof Runtime);
    }
}
