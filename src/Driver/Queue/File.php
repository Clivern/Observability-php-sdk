<?php

declare(strict_types=1);

/*
 * This file is part of Metric - Metrics Collector SDK in PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Metric\Driver\Queue;

use Clivern\Metric\Contract\QueueDriverContract;

/**
 * File Class.
 */
class File implements QueueDriverContract
{
    /**
     * @var int
     */
    private $chunkSize;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $filePattern;

    /**
     * @var string
     */
    private $pendingFilePattern;

    /**
     * Class Constructor.
     */
    public function __construct(
        string $path,
        int $chunkSize = 20,
        string $filePattern = 'metricsFileQueue.{num}.log',
        string $pendingFilePattern = 'metricsFileQueue.pending.log'
    ) {
        $this->path = $path;
        $this->chunkSize = $chunkSize;
        $this->filePattern = $filePattern;
        $this->pendingFilePattern = $pendingFilePattern;
    }

    /**
     * Push value into queue.
     *
     * @param mixed $value
     */
    public function push(array $value): bool
    {
        $value = json_encode($value);
        $pendingFileName = sprintf(
            '%s/%s',
            rtrim($this->path, '/'),
            $this->pendingFilePattern
        );

        $pendingFileContent = '';

        if (file_exists($pendingFileName)) {
            $pendingFileContent = file_get_contents($pendingFileName);
        }

        $pendingFileContent = !empty($pendingFileContent) ? explode("\n", $pendingFileContent) : [];

        if (\count($pendingFileContent) < $this->chunkSize) {
            $pendingFileContent[] = $value;
            file_put_contents($pendingFileName, trim(implode("\n", $pendingFileContent)));

            return true;
        }

        $newFileName = sprintf(
            '%s/%s',
            rtrim($this->path, '/'),
            $this->filePattern
        );

        $files = $this->inspect();
        $num = 1;
        if (!empty($files)) {
            $lastOne = $files[\count($files) - 1];
            $lastOne = explode('.', $lastOne);
            $num = (int) ($lastOne[1]) + 1;
        }

        rename($pendingFileName, str_replace('{num}', $num, $newFileName));
        file_put_contents($pendingFileName, trim(implode("\n", [$value])));

        return true;
    }

    /**
     * Removes and returns the value at the front of the queue.
     */
    public function pop(int $size = 1): array
    {
        $result = [];

        while (0 !== $size) {
            $files = $this->inspect();
            if (empty($files)) {
                return $result;
            }

            $filePath = sprintf(
                '%s/%s',
                rtrim($this->path, '/'),
                $files[0]
            );

            $content = file_get_contents($filePath);
            $contentItems = explode("\n", $content);

            $i = 0;
            foreach ($contentItems as $item) {
                if (0 === $size) {
                    file_put_contents($filePath, trim(implode("\n", \array_slice($contentItems, $i))));

                    return $result;
                }

                $result[] = json_decode($item, true);
                $size = $size - 1;
                ++$i;
            }

            unlink($filePath);
        }

        return $result;
    }

    /**
     * Get size of the queue.
     */
    public function size(): int
    {
        $files = $this->inspect();

        $pendingFileName = sprintf(
            '%s/%s',
            rtrim($this->path, '/'),
            $this->pendingFilePattern
        );

        $pendingFileContent = '';

        if (file_exists($pendingFileName)) {
            $pendingFileContent = @file_get_contents($pendingFileName);
        }

        $pendingFileContent = !empty($pendingFileContent) ? explode("\n", $pendingFileContent) : [];

        return (\count($files) * $this->chunkSize) + \count($pendingFileContent);
    }

    /**
     * Check if queue is empty.
     */
    public function isEmpty(): bool
    {
        return 0 === $this->size();
    }

    /**
     * Cleanup the queue.
     */
    public function clean(): bool
    {
        $files = $this->inspect();
        foreach ($files as $file) {
            unlink(sprintf(
                '%s/%s',
                rtrim($this->path, '/'),
                $file
            ));
        }

        $pendingFileName = sprintf(
            '%s/%s',
            rtrim($this->path, '/'),
            $this->pendingFilePattern
        );

        if (file_exists($pendingFileName)) {
            unlink($pendingFileName);
        }

        return $this->isEmpty();
    }

    /**
     * Inspect Store Dir.
     */
    private function inspect(): array
    {
        $files = scandir($this->path);
        $pattern = explode('.', $this->filePattern);
        $result = [];

        foreach ($files as $file) {
            if (false === mb_strpos($file, $pattern[0])) {
                continue;
            }

            if (false !== mb_strpos($file, $this->pendingFilePattern)) {
                continue;
            }
            $result[] = $file;
        }

        sort($result);

        return $result;
    }
}
