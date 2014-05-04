# Usage

There's many ways to manipulate a file, which is why Redaktilo has been designed
to be able to provide many kinds of editors.

Currently, the only editor available is the `LineEditor` which manipulates
lines, but a `PhpEditor` manipulating PHP tokens could be easily implemented.

Here's the [LineEditor usage manual](02-line-editor).

Whatever the type of the editor, the API should always be the same:

```php
<?php

namespace Gnugat\Redaktilo\Editor;

interface Editor
{
    /**
     * Opens the given file and sets the cursor to its begining.
     */
    public function open($filename);

    /**
     * Moves down or up the cursor in the file to the given location.
     */
    public function jumpDownTo($to);
    public function jumpUpTo($to);

    /**
     * Inserts the given content before or after the cursor.
     * Note 1: after the insertion, the cursor will be set to the inserted content.
     * Note 2: changes are only done in memory, see the `save` method.
     */
    public function addBefore($add);
    public function addAfter($add);

    /**
     * Actually applies the changes to the file.
     */
    public function save();
}
```

## Advised readings

* [Use cases introduction](../use-cases/01-introduction.md)
* [YAML configuration edition](../use-cases/02-yaml-configuration-edition.md)
* [JSON configuration edition](../use-cases/03-json-configuration-edition.md)
* [PHP source code edition](../use-cases/04-php-source-code-edition.md)
* [Global introduction](../01-introduction.md)
* [LineEditor usage](../usage/02-usage.md)
