<?php

declare(strict_types=1);

/*
 * This file is part of Symfony Observability SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Reporter;

use Clivern\Observability\Exception\GraphiteException;

/**
 * GraphiteClient Class.
 */
class GraphiteClient implements ReporterInterface
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

    /**
     * @var int
     */
    private $connectRetries;

    private $connection;

    /**
     * Class Constructor.
     *
     * @param mixed $connectRetries
     */
    public function __construct(
        string $host = 'localhost',
        int $port = 2003,
        string $protocol = 'tcp',
        $connectRetries = 2
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->connectRetries = $connectRetries;
    }

    /**
     * {@inheritdoc}
     */
    public function report(array $metrics)
    {
        if (!$this->connection) {
            $this->connect();
        }

        try {
            foreach ($metrics as $value) {
                fwrite($this->connection, sprintf(
                    \is_float($value['value']) ? "%s %.18f %d\n" : "%s %d %d\n",
                    $value['key'],
                    $value['value'],
                    $value['time']
                ));
            }
        } catch (\Exception $e) {
            throw new GraphiteException(sprintf('Error while reporting to graphite server: %s', $e->getMessage()));
        }

        $this->close();
    }

    /**
     * Establish a connection.
     */
    protected function connect()
    {
        $retry = 1;

        start:
        try {
            ++$retry;
            $this->connection = fsockopen($this->protocol.'://'.$this->host, $this->port);
        } catch (Exception $e) {
            if ($retry <= $this->connectRetries) {
                usleep(100000);
                goto start;
            }
            throw new GraphiteException(sprintf('Error while connecting graphite server: %s', $e->getMessage()));
        }

        if (!$this->connection) {
            throw new GraphiteException('Error! Connection not established!');
        }
    }

    /**
     * Close Connection.
     */
    protected function close()
    {
        if ($this->connection) {
            fclose($this->connection);
        }
    }
}
