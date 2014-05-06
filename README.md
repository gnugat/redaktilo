# Redaktilo

Small and simple library allowing your code to edit files.

**Caution**: under heavy development, new implementations and backward
compatibility breaks are to be expected quite often.

Read more about this library in [its documentation introduction](doc/01-introduction.md).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08/big.png)](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08)
[![Travis CI](https://travis-ci.org/gnugat/redaktilo.png)](https://travis-ci.org/gnugat/redaktilo)

## Installation

Use [Composer](http://getcomposer.org/) to download and install Redaktilo in
your projects:

    composer require "gnugat/redaktilo:~0.2@dev"

## Getting started

Let's say we have the following configuration file:

```yaml
# File: /tmp/config.yaml
security:
    encoders:
        # Examples:
        Acme\DemoBundle\Entity\User1:
            algorithm: sha512
            encode_as_base64: true
            iterations: 5000

        Acme\DemoBundle\Entity\User2:
            encode_as_base64: true
            iterations: 5000
```

If we want to insert `algorithm: sha512` after `Acme\DemoBundle\Entity\User2`,
we can use the following script:

```php
#!/usr/bin/env php
<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Gnugat\Redaktilo\Filesystem\Filesystem;
use Gnugat\Redaktilo\Editor;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

$symfonyFilesystem = new SymfonyFilesystem();
$filesystem = new Filesystem($symfonyFilesystem);
$editor = new Editor($filesystem);

$file = $editor->open('/tmp/config.yaml');
$editor->jumpDownTo($file, '            encode_as_base64: true');
$editor->jumpDownTo($file, '            encode_as_base64: true');
$editor->addBefore($file, '            algorithm: sha512');
$editor->save($file);
```

**Note**: the usage of the
[Symfony2 Yaml component](http://symfony.com/doc/current/components/yaml/introduction.html)
wouldn't help you in this situation if you want to keep empty lines and
comments.

Find out about how to use it with the [usage guide](doc/usage/01-index.md).

## Tests

Run the following script to run the tests:

    ./bin/tester.sh

## Further documentation

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/gnugat/redaktilo/releases)
* the file listing the [changes between versions](CHANGELOG.md)

You can find more documentation at the following links:

* [copyright and MIT license](LICENSE)
* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)
* [documentation directory](doc)
