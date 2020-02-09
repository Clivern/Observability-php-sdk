<p align="center">
    <img alt="Metric Logo" src="https://raw.githubusercontent.com/clivern/Metric/master/assets/img/logo.png?v=1.0.1" width="130" />
    <h3 align="center">Metric</h3>
    <p align="center">Metrics Collector SDK in PHP</p>
    <p align="center">
        <a href="https://travis-ci.org/Clivern/Metric"><img src="https://travis-ci.org/Clivern/Metric.svg?branch=master"></a>
        <a href="https://packagist.org/packages/clivern/metric"><img src="https://img.shields.io/badge/Version-0.0.1-red.svg"></a>
        <a href="https://github.com/Clivern/Metric/blob/master/LICENSE"><img src="https://img.shields.io/badge/LICENSE-MIT-orange.svg"></a>
    </p>
</p>


## Documentation

### Installation:

To install the package via `composer`, use the following:

```zsh
$ composer require clivern/metric
```

This command requires you to have `composer` installed globally.

### Basic Usage:

After adding the package as a dependency, Please read the following steps:

1. Run a simple `tcp` server.

```php
$ nc -l localhost 2003
```

2. Create a script to persist metrics to `Graphite`

```php
<?php

use Clivern\Metric\Metric;
use Clivern\Metric\Util\Config;
use Clivern\Metric\Driver\Queue\File;
use Clivern\Metric\Driver\Storage\Graphite;


$metric = new Metric(
    new Config(),
    new Graphite('localhost', 2003, 'tcp'),
    new File("/path/to/cache/dir")
);

$metric->persist(true); // true to run as daemon & false to send and close storage connection
```

3. Create a script to push metrics to the middle queue engine `File System` (not preferred to run on production)

```php
<?php

use Clivern\Metric\Metric;
use Clivern\Metric\Util\Config;
use Clivern\Metric\Driver\Queue\File;
use Clivern\Metric\Driver\Storage\Graphite;


$metric = new Metric(
    new Config(),
    new Graphite('localhost', 2003, 'tcp'),
    new File("/path/to/cache/dir")
);

$i = 1;

while ($i < 100) {
    $metric->publish("app.metric", "{$i}", new \DateTime("now", new \DateTimeZone('UTC')));
    ++$i;
}
```


## Versioning

For transparency into our release cycle and in striving to maintain backward compatibility, Metric is maintained under the [Semantic Versioning guidelines](https://semver.org/) and release process is predictable and business-friendly.

See the [Releases section of our GitHub project](https://github.com/clivern/metric/releases) for changelogs for each release version of Metric. It contains summaries of the most noteworthy changes made in each release.


## Bug tracker

If you have any suggestions, bug reports, or annoyances please report them to our issue tracker at https://github.com/clivern/metric/issues


## Security Issues

If you discover a security vulnerability within Metric, please send an email to [hello@clivern.com](mailto:hello@clivern.com)


## Contributing

We are an open source, community-driven project so please feel free to join us. see the [contributing guidelines](CONTRIBUTING.md) for more details.


## License

© 2020, clivern. Released under [MIT License](https://opensource.org/licenses/mit-license.php).

**Metric** is authored and maintained by [@clivern](http://github.com/clivern).
