<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric;

use Clivern\Metric\Contract\ConfigContract;
use Clivern\Metric\Contract\QueueDriverContract;
use Clivern\Metric\Contract\StorageDriverContract;

/**
 * Metric Class.
 */
class Metric
{
    /**
     * @var ConfigContract
     */
    private $config;

    /**
     * @var QueueDriverContract
     */
    private $queueDriver;

    /**
     * @var StorageDriverContract
     */
    private $storageDriver;

    /**
     * @var string
     */
    private $bucketName;

    public function __construct(
        ConfigContract $config,
        StorageDriverContract $storageDriver,
        QueueDriverContract $queueDriver
    ) {
        $this->config = $config;
        $this->storageDriver = $storageDriver;
        $this->queueDriver = $queueDriver;
    }

    /**
     * Create a Bucket to Collect Metrics.
     */
    public function createBucket(string $name)
    {
        $this->bucketName = $name;
    }

    /**
     * Push Metric into The Queue.
     */
    public function publish(string $name, string $value, \DateTime $datetime): bool
    {
    }

    /**
     * Move Metrics from the Queue to Storage.
     */
    public function persist(): bool
    {
    }
}
