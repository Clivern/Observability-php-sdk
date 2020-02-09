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
use Clivern\Metric\Util\ConfigValue;

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
     * Class Constructor.
     */
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
     * Push Metric into The Queue.
     */
    public function publish(string $key, string $value, \DateTime $datetime): bool
    {
        return $this->queueDriver->push([
            'key' => $key,
            'value' => $value,
            'time' => $datetime->getTimestamp(),
        ]);
    }

    /**
     * Move Metrics from the Queue to Storage.
     */
    public function persist(bool $daemon = false): bool
    {
        $this->storageDriver->connect();

        $persistChunkSize = (int) $this->config->get(
            'storage.persist_chunk_size',
            new ConfigValue(20)
        )->value();

        $delay = (int) $this->config->get(
            'storage.persist_delay',
            new ConfigValue(1000)
        )->value();

        $run = true;

        while ($run) {
            $run = $daemon;
            $this->storageDriver->reconnect();

            if ($this->queueDriver->isEmpty()) {
                usleep($delay);
                continue;
            }

            $this->storageDriver->persist($this->queueDriver->pop($persistChunkSize));

            usleep($delay);
        }

        $this->storageDriver->close();
    }
}
