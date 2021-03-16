<p align="center">
    <img alt="observability-php-sdk logo" src="/assets/img/logo5.png?v=2.0.7" />
    <h3 align="center">Observability SDK</h3>
    <p align="center">Observability SDK for PHP Applications</p>
    <p align="center">
        <a href="https://github.com/Clivern/observability-php-sdk/actions/workflows/php.yml">
            <img src="https://github.com/Clivern/observability-php-sdk/actions/workflows/php.yml/badge.svg">
        </a>
        <a href="https://packagist.org/packages/clivern/observability-php-sdk">
            <img src="https://img.shields.io/badge/Version-2.0.7-red.svg">
        </a>
        <a href="https://github.com/Clivern/observability-php-sdk/blob/master/LICENSE">
            <img src="https://img.shields.io/badge/LICENSE-MIT-orange.svg">
        </a>
    </p>
</p>


## Documentation

### Installation:

To install the package via `composer`, use the following:

```zsh
$ composer require clivern/observability-php-sdk
```

This command requires you to have `composer` installed globally.

### Graphite Reporter:

```php
use Clivern\Observability\Aggregation\MemcachedAggregate;
use Clivern\Observability\Aggregation\Client\MemcachedClient;
use Clivern\Observability\Reporter\GraphiteClient;


$metricsReporter = new MemcachedAggregate(
    new GraphiteClient('localhost', 2003),
    new MemcachedClient('127.0.0.1', 11211),
    []
);

$metricsReporter->report([
    [
        'key' => 'orders_service.metrics.total_http_calls',
        'value' => 1,
        'time' => time(),
        'aggregateFunc' => MemcachedAggregate::SUM_AGGREGATE_FUNCTION
    ]
]);
```

For `PHP` runtime statistics, You can use this class `Clivern\Observability\Stats\Runtime`.

To measure the execution time:

```php
use Clivern\Observability\Stats\Execution;


$execution = new Execution();
$execution->start();

// Code that takes time!
sleep(2);

$execution->end();

var_dump($execution->getTimeInSeconds()); // float
var_dump($execution->getTimeInMinutes()); // float
```

To measure latency of an HTTP call or application latency.

```php
use Clivern\Observability\Aggregation\MemcachedAggregate;
use Clivern\Observability\Aggregation\Client\MemcachedClient;
use Clivern\Observability\Reporter\GraphiteClient;


$metricsReporter = new MemcachedAggregate(
    new GraphiteClient('localhost', 2003),
    new MemcachedClient('127.0.0.1', 11211),
    []
);

$execution = new Execution();
$execution->start();

// Code that takes time!
sleep(2);

$execution->end();

$metricsReporter->report([
    [
        'key' => 'orders_service.metrics.http_request_latency',
        'value' => $execution->getTimeInSeconds(),
        'time' => time(),
        'aggregateFunc' => MemcachedAggregate::AVG_AGGREGATE_FUNCTION
    ]
]);
```

### Elasticsearch Reporter:

```php
#
```


## Versioning

For transparency into our release cycle and in striving to maintain backward compatibility, observability-php-sdk is maintained under the [Semantic Versioning guidelines](https://semver.org/) and release process is predictable and business-friendly.

See the [Releases section of our GitHub project](https://github.com/clivern/observability-php-sdk/releases) for changelogs for each release version of observability-php-sdk. It contains summaries of the most noteworthy changes made in each release.


## Bug tracker

If you have any suggestions, bug reports, or annoyances please report them to our issue tracker at https://github.com/clivern/observability-php-sdk/issues


## Security Issues

If you discover a security vulnerability within observability-php-sdk, please send an email to [hello@clivern.com](mailto:hello@clivern.com)


## Contributing

We are an open source, community-driven project so please feel free to join us. see the [contributing guidelines](CONTRIBUTING.md) for more details.


## License

© 2020, clivern. Released under [MIT License](https://opensource.org/licenses/mit-license.php).

**observability-php-sdk** is authored and maintained by [@clivern](http://github.com/clivern).
