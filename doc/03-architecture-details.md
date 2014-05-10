# Architecture details

This chapter explains the responsibility of each classes:

* [File](#file)
* [Filesystem](#filesystem)
* [Editor](#editor)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## File

Redaktilo is based on this domain object:

```php
<?php

namespace Gnugat\Redaktilo;

class File
{
    public function getFilename();

    public function read();
    public function write($newContent);

    // ...
}
```

The rest are only stateless services allowing you to manipulate it.

Currently the file also have a cursor to the current line and the possibility
to convert the content into an array of lines:

```php
<?php

namespace Gnugat\Redaktilo;

class File
{
    // ...

    public function readlines();
    public function writelines(array $newLines);

    public function getCurrentLineNumber();
    public function setCurrentLineNumber($lineNumber);

    public function insertLineAt($line, $lineNumber);
    public function changeLineTo($line, $lineNumber);
    public function removeLine($lineNumber);
}
```

## Filesystem

A service which does the actual read and write operations:

```php
<?php

namespace Gnugat\Redaktilo;

class Filesystem
{
    public function open($filename);
    public function create($filename);

    public function exists($filename);

    public function write(File $file);

    public function detectLineBreak($content);
}
```

You can only open existing files and create new files. Those two methods will
create an instance of `File`.

The `detectLineBreak` method looks in the file's content to guess the line
break: windows (`\r\n`) or other (`\n`).
If there's no line yet, the system's one is used (PHP_EOL`).

**Note**: `Filesystem` uses the
[Symfony2 Filesystem component](http://symfony.com/doc/current/components/filesystem.html).

## Editor

`Editor` is intended to be a facade using every other services. It provides a
unique API to developers with the editor metaphor.

## Next readings

* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
* [Usage](doc/01-usage.md)
* [Use cases](doc/02-use-cases.md)
