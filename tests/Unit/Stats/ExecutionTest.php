<?php

declare(strict_types=1);

/*
 * This file is part of Observability PHP SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Unit\Stats;

use PHPUnit\Framework\TestCase;
use Clivern\Observability\Stats\Execution;

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

        $this->assertTrue($execution instanceof Execution);
        $this->assertTrue($execution->getTimeInSeconds() > 0);
    }
}
