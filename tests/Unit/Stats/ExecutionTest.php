<?php

declare(strict_types=1);

/*
 * This file is part of Observability PHP SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Stats;

use Clivern\Observability\Stats\Execution;
use PHPUnit\Framework\TestCase;

/**
 * Execution Class Test.
 */
class ExecutionTest extends TestCase
{
    public function testResult()
    {
        $execution = new Execution();
        $execution->start();
        usleep(100);
        $execution->end();

        self::assertTrue($execution instanceof Execution);
        self::assertTrue($execution->getTimeInSeconds() > 0);
    }
}
