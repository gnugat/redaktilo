# Redaktilo

Redaktilo allows you to find, insert, replace and remove lines using an
editor-like object.

*Because your code too needs an editor to manipulate files*.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08/mini.png)](https://insight.sensiolabs.com/projects/fbe2d89f-f64d-45c2-a680-bbafac4b0d08)
[![Travis CI](https://travis-ci.org/gnugat/redaktilo.png)](https://travis-ci.org/gnugat/redaktilo)

## Getting started

Use [Composer](http://getcomposer.org/) to install Redaktilo in your projects:

    composer require "gnugat/redaktilo:~1.0"

Redaktilo provides an `Editor` class which can be instanciated using
`EditorFactory`:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
```

## Real life example

For our example, we will create a [`KernelManipulator`](https://github.com/sensiolabs/SensioGeneratorBundle/blob/8b7a33aa3d22388443b6de0b0cf184122e9f60d2/Manipulator/KernelManipulator.php)
similar to the one we can find in [SensioGeneratorBundle](https://github.com/sensiolabs/SensioGeneratorBundle).

It takes a bundle's fully qualified classname and inserts it in the `AppKernel`
file:

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
        $appKernel = $this->editor->open($this->appKernelFilename);
        $newBundle = "            new $bundle(),";
        if ($this->editor->hasBelow($appKernel, $newBundle)) {
            $message = sprintf('Bundle "%s" is already defined in "AppKernel::registerBundles()".', $bundle);

            throw new \RuntimeException($message);
        }
        $this->editor->jumpBelow($appKernel, '        );');
        $this->editor->insertAbove($appKernel, $newBundle);
        $this->editor->save($appKernel);

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
* [migration to 2.0 instructions](UPGRADE-2.0.md)

Next readings:

* [Tutorial](doc/01-tutorial.md)
* [Use cases](doc/02-use-cases.md)
* [Reference](doc/03-reference.md)
* [Vocabulary](doc/04-vocabulary.md)
* [Extending](doc/05-extending.md)
* [Exceptions](doc/06-exceptions.md)
