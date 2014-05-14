# Architecture details

This chapter explains the responsibility of each classes:

* [File](#file)
* [Filesystem](#filesystem)
* [LineNumber](#linenumber)
* [SearchStrategy](#searchstrategy)
  * [LineSearchStrategy](#linesearchstrategy)
  * [LineNumberSearchStrategy](#linenumbersearchstrategy)
* [SearchEngine](#searchengine)
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

Every single other classes in this project are stateless services allowing you
to manipulate it.

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
    public function hasLine($line);
}
```

I wouldn't rely on them too much as they might be moved outside.

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
}
```

You can only open existing files and create new files. The first two methods
will create an instance of `File`.

There's another method, `detectLineBreak`, which looks in the file's content to
guess the line break: windows (`\r\n`) or other (`\n`).
If there's no line yet, the system's one is used (`PHP_EOL`).

This one too might be extracted, so use it with caution.

**Note**: `Filesystem` depends on the
[Symfony2 Filesystem component](http://symfony.com/doc/current/components/filesystem.html).

## LineNumber

A convenient Method Factory allowing you to make your code easier to read. Its
methods normalize the given line number and return it, to make sure you use only
positive integers.

```php
<?php

namespace Gnugat\Redaktilo\Search\FactoryMethod;

class LineNumber
{
    public static function absolute($lineNumber);

    public static function down($lines);
    public static function up($lines);
}
```

## SearchStrategy

Another stateless service, whic allows you to search patterns in the File's
content.

This is actually an interface allowing you to extend Redaktilo. By default, two
implementations are provided.

```php
<?php

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\File;

interface SearchStrategy
{
    public function has(File $file, $pattern);

    public function findNext(File $file, $pattern);
    public function findPrevious(File $file, $pattern);

    public function supports($pattern);
}
```

### LineSearchStrategy

If you know exactly the value of the line you want to look for, use this one.

The `has` method will look in the whole file and will return `true` if at least
one line matches exactly the given one.

The `find` methods will return the line number.

### LineNumberSearchStrategy

If you want to go to a given line number, use this one.

The `findNext` method will jump `n` lines under the current one,  while
`findPrevious` will jump above.

The `has` method just checks that the given line number is within the boundary
of the file.

## SearchEngine

Allows you to register many `SearchStrategy` and to return the one that supports
the given pattern:

```php
<?php

namespace Gnugat\Redaktilo\Search;

class SearchEngine
{
    public function registerStrategy(SearchStrategy $searchStrategy);
    public function resolve($pattern);
}

```

## Editor

`Editor` is intended to be a facade using every other services. It provides
developers a unique API implementing the editor metaphor.

## Next readings

* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
* [Usage](doc/01-usage.md)
* [Use cases](doc/02-use-cases.md)
