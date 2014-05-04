# Architecture details

Redaktilo is composed of 3 classes:

* [File](#file)
* [Filesystem](#filesystem)
* [Editor](#editor)

## File

A domain model which incorporates data and behavior. It has:

* a `filename`
* a `content`

And it allows you to:

* read the content in a special format
* write the content in a special format

`LineFile` splits its content by lines.

## Filesystem

A service which does the actual read and write operations.

When asked to read a file, it creates an instance of `File`.

When asked to write a file, it reads the unformated content of `File` and puts
it on the file system.

## Editor

This is the public API of this library, developers shouldn't have to use any
other class:

```php
<?php

namespace Gnugat\Redaktilo\Editor;

class LineEditor implements Editor
{
    /**
     * Calls Filesystem to open a file and sets the cursor to its first line.
     */
    public function open($filename);

    /**
     * Moves down or up the cursor in the file to the given line.
     */
    public function jumpDownTo($to);
    public function jumpUpTo($to);

    /**
     * Inserts the given line before or after the cursor.
     * Note 1: after the insertion, the cursor will be set to the new line.
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
