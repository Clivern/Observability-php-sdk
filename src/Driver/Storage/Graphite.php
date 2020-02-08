<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Driver\Storage;

use Clivern\Metric\Contract\StorageDriverContract;

/**
 * Graphite Class.
 */
class Graphite implements StorageDriverContract
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $protocol;

    private $connection;

    /**
     * Class Constructor.
     */
    public function __construct(string $host = 'localhost', int $port = 2003, string $protocol = 'tcp')
    {
        $this->host = $host;
        $this->port = $port;
        $this->protocol = $protocol;
    }

    /**
     * Establish a connection.
     */
    public function connect(): bool
    {
        try {
            $this->connection = fsockopen($this->protocol.'://'.$this->host, $this->port);
            if (!$this->connection) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Reconnect.
     */
    public function reconnect(): bool
    {
        if ($this->connection) {
            return true;
        }

        try {
            $this->connection = fsockopen($this->protocol.'://'.$this->host, $this->port);
            if (!$this->connection) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Store a set of values.
     */
    public function persist(array $values): bool
    {
        if (!$this->connection) {
            throw new Exception('Error! Connection not established!');
        }

        try {
            foreach ($values as $value) {
                fwrite($this->connection, sprintf(
                    \is_float($value['value']) ? "%s %.18f %d\n" : "%s %d %d\n",
                    $value['key'],
                    $value['value'],
                    $value['time']
                ));
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Close Connection.
     */
    public function close(): bool
    {
        if ($this->connection) {
            fclose($this->connection);
        }

        return true;
    }
}
