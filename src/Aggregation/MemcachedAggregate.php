<?php

declare(strict_types=1);

/*
 * This file is part of Observability PHP SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Aggregation;

use Clivern\Observability\Aggregation\Client\MemcachedClient;
use Clivern\Observability\Exception\MemcachedException;
use Clivern\Observability\Reporter\ReporterInterface;

/**
 * MemcachedAggregate Class.
 */
final class MemcachedAggregate implements AggregationInterface
{
    public const DEFAULT_OPTIONS = [
        'cache_key_prefix' => 'clv_observability',
        'batch_interval' => 60, // In seconds
    ];

    public const SUM_AGGREGATE_FUNCTION = 'SUM';
    public const AVG_AGGREGATE_FUNCTION = 'AVG';
    public const MAX_AGGREGATE_FUNCTION = 'MAX';
    public const MIN_AGGREGATE_FUNCTION = 'MIN';

    /**
     * @var \MemcachedClient
     */
    private $memcachedClient;

    /**
     * @var \ReporterInterface
     */
    private $reporter;

    /**
     * @var array
     */
    private $options;

    public function __construct(
        ReporterInterface $reporter,
        MemcachedClient $memcachedClient,
        array $options = []
    ) {
        $this->options = array_merge(self::DEFAULT_OPTIONS, $options);
        $this->memcachedClient = $memcachedClient ?? new MemcachedClient();
        $this->reporter = $reporter;
    }

    /**
     * {@inheritdoc}
     *
     * @throws MemcachedException
     */
    public function report(array $metrics): void
    {
        try {
            $status = false;
            $aggregatedMetrics = [];

            $this->memcachedClient->ping();

            while (!$status) {
                $result = $this->memcachedClient->get(
                    sprintf('%s_metrics', $this->options['cache_key_prefix']),
                    null,
                    MemcachedClient::GET_EXTENDED
                );

                $metrics = $this->aggregateMetrics($metrics);

                if (empty($result)) {
                    $this->memcachedClient->set(
                        sprintf('%s_metrics', $this->options['cache_key_prefix']),
                        json_encode($metrics)
                    );
                    $this->memcachedClient->quit();

                    return;
                }

                $result['value'] = array_merge(
                    json_decode($result['value'], true),
                    $metrics
                );

                $result['value'] = $this->aggregateMetrics($result['value']);

                // If batch reporting interval passed and enabled, send metrics to reporter
                if ($this->isBatchIntervalPassed()) {
                    $aggregatedMetrics = $result['value'];
                    $result['value'] = [];
                    $this->resetBatchInterval();
                }

                $status = $this->memcachedClient->compareAndSwap(
                    $result['cas'],
                    sprintf('%s_metrics', $this->options['cache_key_prefix']),
                    json_encode($result['value'])
                );
            }
            $this->memcachedClient->quit();
        } catch (Exception $e) {
            throw new MemcachedException(sprintf('Error while calling memcached server: %s', $e->getMessage()));
            // If memcached is down or there was any failure happened, send reported metrics directly to reporter
            if (empty($aggregatedMetrics) && !empty($metrics)) {
                $this->reporter->report($metrics);
            }
        } finally {
            // Send all aggregated metrics only if reporting time reached and memcached value got cleared.
            if ($status && !empty($aggregatedMetrics)) {
                $this->reporter->report($aggregatedMetrics);
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws MemcachedException
     */
    public function flush(): array
    {
        try {
            $status = false;

            $this->memcachedClient->ping();

            while (!$status) {
                $result = $this->memcachedClient->get(
                    sprintf('%s_metrics', $this->options['cache_key_prefix']),
                    null,
                    MemcachedClient::GET_EXTENDED
                );

                if (empty($result)) {
                    $this->memcachedClient->quit();

                    return [];
                }

                $status = $this->memcachedClient->compareAndSwap(
                    $result['cas'],
                    sprintf('%s_metrics', $this->options['cache_key_prefix']),
                    serialize([])
                );
            }

            $this->memcachedClient->quit();

            return json_decode($result['value'], true);
        } catch (Exception $e) {
            throw new MemcachedException(sprintf('Error while calling memcached server: %s', $e->getMessage()));
        }

        return [];
    }

    /**
     * Aggregate metrics with an aggregate function.
     */
    private function aggregateMetrics(array $metrics): array
    {
        $newMetrics = [];

        foreach ($metrics as $metric) {
            if (!\in_array($metric['key'], array_keys($newMetrics), true)) {
                $newMetrics[$metric['key']] = $metric;
                continue;
            }

            if (self::SUM_AGGREGATE_FUNCTION === $newMetrics[$metric['key']]['aggregateFunc']) {
                $newMetrics[$metric['key']]['value'] = $metric['value'] + $newMetrics[$metric['key']]['value'];
                $newMetrics[$metric['key']]['time'] = (int) (($metric['time'] + $newMetrics[$metric['key']]['time']) / 2);
            } elseif (self::AVG_AGGREGATE_FUNCTION === $newMetrics[$metric['key']]['aggregateFunc']) {
                $newMetrics[$metric['key']]['value'] = $metric['value'] + $newMetrics[$metric['key']]['value'] / 2;
                $newMetrics[$metric['key']]['time'] = (int) (($metric['time'] + $newMetrics[$metric['key']]['time']) / 2);
            } elseif (self::MAX_AGGREGATE_FUNCTION === $newMetrics[$metric['key']]['aggregateFunc']) {
                if ($newMetrics[$metric['key']]['value'] < $metric['value']) {
                    $newMetrics[$metric['key']] = $metric;
                }
            } elseif (self::MIN_AGGREGATE_FUNCTION === $newMetrics[$metric['key']]['aggregateFunc']) {
                if ($newMetrics[$metric['key']]['value'] > $metric['value']) {
                    $newMetrics[$metric['key']] = $metric;
                }
            }
        }

        return array_values($newMetrics);
    }

    /**
     * Is Batch Interval Passed.
     */
    private function isBatchIntervalPassed(): bool
    {
        if ($this->options['batch_interval'] <= 0) {
            return false;
        }

        $result = $this->memcachedClient->get(
            sprintf('%s_batch_ts', $this->options['cache_key_prefix']),
            null,
            MemcachedClient::GET_EXTENDED
        );

        if (empty($result)) {
            return true;
        }

        return ($result['value'] + $this->options['batch_interval']) <= time();
    }

    /**
     * Reset Batch Interval.
     *
     * @throws MemcachedException
     */
    private function resetBatchInterval(): bool
    {
        if ($this->options['batch_interval'] <= 0) {
            return false;
        }

        try {
            $status = false;

            $this->memcachedClient->ping();

            while (!$status) {
                $result = $this->memcachedClient->get(
                    sprintf('%s_batch_ts', $this->options['cache_key_prefix']),
                    null,
                    MemcachedClient::GET_EXTENDED
                );

                if (empty($result)) {
                    $this->memcachedClient->set(
                        sprintf('%s_batch_ts', $this->options['cache_key_prefix']),
                        time()
                    );

                    $this->memcachedClient->quit();

                    return true;
                }

                $status = $this->memcachedClient->compareAndSwap(
                    $result['cas'],
                    sprintf('%s_batch_ts', $this->options['cache_key_prefix']),
                    time()
                );
            }

            $this->memcachedClient->quit();
        } catch (Exception $e) {
            throw new MemcachedException(sprintf('Error while calling memcached server: %s', $e->getMessage()));
        }

        return true;
    }
}
