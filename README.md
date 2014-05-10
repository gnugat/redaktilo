# Redaktilo

*Because your code too needs an editor to manipulate files*.

Redaktilo provides an Object Oriented way to manipulate files, through the
editor metaphor:

* you can open a file
* you can then navigate in the file to select a line
* next, you can insert a new line above/under the current line or change the
  current line
* finally you can save the changes on the filesystem

**Caution**: still under heavy development (but BC breaks might not be frequent).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08/big.png)](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08)
[![Travis CI](https://travis-ci.org/gnugat/redaktilo.png)](https://travis-ci.org/gnugat/redaktilo)

## Getting started

Use [Composer](http://getcomposer.org/) to download and install Redaktilo in
your projects:

    composer require "gnugat/redaktilo:~0.3@dev"

The only class you should need to work with is the stateless service `Editor`,
which means you can create it once and use it everywhere in your application:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Editor;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

$symfonyFilesystem = new SymfonyFilesystem();
$filesystem = new Filesystem($symfonyFilesystem);
$editor = new Editor($filesystem);
```

We'll describe here the
[SensioGeneratorBundle](https://github.com/sensiolabs/SensioGeneratorBundle)
use case: its [KernelManipulator](https://github.com/sensiolabs/SensioGeneratorBundle/blob/8b7a33aa3d22388443b6de0b0cf184122e9f60d2/Manipulator/KernelManipulator.php)
edits a class to insert a line.

Here's what the code would look like if it was using Redaktilo:

```php
<?php

namespace Sensio\Bundle\GeneratorBundle\Manipulator;

use Gnugat\Redaktilo\Editor;

class KernelManipulator extends Manipulator
{
    protected $editor;
    protected $appKernelFilename;

    public function __construct(Editor $editor, $appKernelFilename)
    {
        $this->editor = $editor;
        $this->appKernelFilename = $appKernelFilename;
    }

    public function addBundle($bundle)
    {
        $file = $this->editor->open($this->appKernelFilename);
        $lineToFind = '        );';
        $newLine = sprintf('            new %s(),', $bundle);

        $this->editor->jumpDownTo($file, $lineToFind);
        $this->editor->addBefore($file, $newLine);

        $this->editor->save($file);

        return true;
    }
}
```

As you can see it's easier to read and to understand than
[the original PHP token parsing](https://github.com/sensiolabs/SensioGeneratorBundle/blob/8b7a33aa3d22388443b6de0b0cf184122e9f60d2/Manipulator/KernelManipulator.php).

## Further documentation

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/gnugat/redaktilo/releases)
* the file listing the [changes between versions](CHANGELOG.md)

You can find more documentation at the following links:

* [copyright and MIT license](LICENSE)
* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)

Next readings:

* [Usage](doc/01-usage.md)
* [Use cases](doc/02-use-cases.md)
* [Architecture details](doc/03-architecture-details.md)
* [Vocabulary](04-vocabulary.md)
