## PHP source code edition

[SensioGeneratorBundle](https://github.com/sensiolabs/SensioGeneratorBundle)
allows its user, among other things, to generate new bundles: it will create the
files and edit the `app/AppKernel.php` file to add a new line.

The file edition is done with [KernelManipulator](https://github.com/sensiolabs/SensioGeneratorBundle/blob/8b7a33aa3d22388443b6de0b0cf184122e9f60d2/Manipulator/KernelManipulator.php)
which parses PHP tokens and follows this logic:

1. retrieve the code from the `registerBundles` method
2. find `$bundles = array(`
3. find `);`
4. insert a new line above

Here's what the code would look like using Redaktilo's `LineEditor`:

```php
<?php

namespace Sensio\Bundle\GeneratorBundle\Manipulator;

use Gnugat\Redaktilo\Editor;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelManipulator extends Manipulator
{
    protected $kernel;
    protected $editor;

    public function __construct(KernelInterface $kernel, Editor $editor)
    {
        $this->kernel = $kernel;
        $this->editor = $editor;
    }

    public function addBundle($bundle)
    {
        $filename = $this->kernel->getRootDir().'/app/AppKernel.php';

        $this->editor->open($filename);

        $this->editor->jumpDownTo('    public function registerBundles()');
        $this->editor->jumpDownTo('        $bundles = array(');
        $this->editor->jumpDownTo('        );');

        $this->editor->addBefore(sprintf('            new %s(),', $bundle));

        $this->editor->save();

        return true;
    }
}
```

As you can see it's easier to read and understand than the original token
parsing (plus the whole Reflection thing has been dropped).

## Advised readings

* [Use cases introduction](01-introduction.md)
* [YAML configuration edition](02-yaml-configuration-edition.md)
* [JSON configuration edition](03-json-configuration-edition.md)
* [Global introduction](../01-introduction.md)
* [Usage introduction](../usage/01-introduction.md)
* [Editor usage](../usage/02-editor.md)
