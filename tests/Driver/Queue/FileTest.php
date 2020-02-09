<?php

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Util;

use Clivern\Metric\Driver\Queue\File;
use PHPUnit\Framework\TestCase;

/**
 * File Class Test.
 */
class FileTest extends TestCase
{
    public function testQueue()
    {
        $file = new File(
            __DIR__.'/../../../cache/',
            1
        );

        $this->assertTrue($file->clean());

        $i = 1;
        while ($i <= 10) {
            $file->push(['metric' => $i]);
            ++$i;
        }

        $this->assertSame(10, $file->size());
        $this->assertSame($file->pop(1), [['metric' => 1]]);
        $this->assertSame($file->pop(9), [
            ['metric' => 2],
            ['metric' => 3],
            ['metric' => 4],
            ['metric' => 5],
            ['metric' => 6],
            ['metric' => 7],
            ['metric' => 8],
            ['metric' => 9],
        ]);
        $this->assertSame(1, $file->size());
    }
}
