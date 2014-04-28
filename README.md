# Redaktilo

A programatic editor:

```php
    #!/usr/bin/env php
    <?php

    require_once __DIR__.'/../../vendor/autoload.php';

    use Gnugat\Redaktilo\File\Filesystem;
    use Gnugat\Redaktilo\Editor\LineEditor;

    $filesystem = new Filesystem();
    $editor = new LineEditor($filesystem);

    $addLine = 'Cat';
    $afterLine = 'Grumpy';

    $editor->open('/tmp/edit-me.txt');
    $editor->addAfter($addLine, $afterLine);
```

Read more about this library in [its introduction](doc/01-introduction.md).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08/big.png)](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08)
[![Travis CI](https://travis-ci.org/gnugat/redaktilo.png)](https://travis-ci.org/gnugat/redaktilo)

## Features

    Operations:

    [x] opens existing file
    [ ] creates new file
    [ ] hold many files
    [ ] autosave configuration
    [x] add after
    [ ] add before
    [ ] selector

    File types:

    [x] lines
    [ ] indented lines
    [ ] PHP tokens

Find out how to use it with the [usage guide](doc/03-usage.md).

## Installation

To download and install this project, run the following command:

    curl -sS https://raw.github.com/gnugat/redaktilo/master/bin/installer.sh | sh

Learn more about the steps followed by the script by reading its [documentation](doc/02-installation.md).

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
