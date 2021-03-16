<?php

declare(strict_types=1);

/*
 * This file is part of Symfony Observability SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Clivern\Observability\Reporter;

/**
 * ElasticsearchClient Class.
 */
class ElasticsearchClient implements ReporterInterface
{
    /**
     * {@inheritdoc}
     */
    public function report(array $metrics)
    {
        // @TODO
    }
}
