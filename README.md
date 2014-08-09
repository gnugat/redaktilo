# Redaktilo

*Because your code too needs an editor to manipulate files*.

Redaktilo provides an Object Oriented way to manipulate files, through the
editor metaphor:

* your code can open a file
* it can then check the presence of a line in it
* it also can navigate in the file to select a line
* next, it can manipulate the current line:
  * insert a new one above/under it
  * replace it
  * remove it
* finally it can save the changes on the filesystem

**Caution**: Project currently in version alpha, expect massive BC breaks.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08/mini.png)](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08)
[![Travis CI](https://travis-ci.org/gnugat/redaktilo.png)](https://travis-ci.org/gnugat/redaktilo)

## Getting started

Use [Composer](http://getcomposer.org/) to download and install Redaktilo in
your projects:

    composer require "gnugat/redaktilo:~1.0.0@alpha"

To use Redaktilo, you have to create an editor. The most simple way to do this
is by using the `EditorFactory`:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
```

I'll describe the
[SensioGeneratorBundle](https://github.com/sensiolabs/SensioGeneratorBundle)
use case in this README. This bundle has a [`KernelManipulator`](https://github.com/sensiolabs/SensioGeneratorBundle/blob/8b7a33aa3d22388443b6de0b0cf184122e9f60d2/Manipulator/KernelManipulator.php)
class which edits an `AppKernel` file to insert a line.

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

        $newLine = sprintf('            new %s(),', $bundle);

        if ($this->editor->has($file, $newLine)) {
            throw new \RuntimeException(sprintf(
                'Bundle "%s" is already defined in "AppKernel::registerBundles()".',
                $bundle
            ));
        }

        $lineToFind = '        );';

        $this->editor->jumpUnder($file, $lineToFind);
        $this->editor->insertAbove($file, $newLine);

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

* [Tutorial](doc/01-tutorial.md)
* [Use cases](doc/02-use-cases.md)
* [Reference](doc/03-reference.md)
* [Vocabulary](doc/04-vocabulary.md)
