# Architecture details

This chapter explains the responsibility of each classes:

* [File](#file)
* [Filesystem](#filesystem)
* [Converter](#converter)
    * [LineContentConverter](#linecontentconverter)
* [DependencyInjection](#dependencyinjection)
* [FactoryMethod](#factorymethod)
    * [Filesystem2](#filesystem2)
    * [Line](#line)
    * [LineNumber](#linenumber)
* [Search](#search)
    * [LineSearchStrategy](#linesearchstrategy)
    * [LineNumberSearchStrategy](#linenumbersearchstrategy)
* [Replace](#replace)
    * [LineReplaceStrategy](#linereplacestrategy)
* [Engine](#engine)
    * [SearchEngine](#searchengine)
    * [ReplaceEngine](#replaceengine)
* [Editor](#editor)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## File

**Redaktilo** is based on this domain object:

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

Currently the file also have a cursor to the current line:

```php
<?php

namespace Gnugat\Redaktilo;

class File
{
    // ...

    public function getCurrentLineNumber();
    public function setCurrentLineNumber($lineNumber);

    public function changeLineTo($line, $lineNumber);
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

**Note**: `Filesystem` depends on the
[Symfony2 Filesystem component](http://symfony.com/doc/current/components/filesystem.html).

## Converter

This interface allows you to extend **Redaktilo** in order to manipulate
different representations of the `File`'s content:

```php
<?php

namespace Gnugat\Redaktilo\Converter;

use Gnugat\Redaktilo\File;

interface ContentConverter
{
    public function from(File $file);
    public function back(File $file, $convertedContent);
}
```

Possible representations might be:

* PHP tokens
* JSON parameters

### LineContentConverter

**Redaktilo** relies heavily on this representation: a `File` should be composed
of lines.

This converter takes the content, detects its line break and splits it into an
array of lines stripped from the line break character.

It is also able to merge back those lines with the appropriate line break.

## DependencyInjection

The `StaticContainer` allows you to lazily create the services.

Yep, "lazily", because you call the same method a second time it will return the
same instance. It is possible because the services are stateless: calling them
many times in different orders doesn' affect their behavior.

```php
<?php

namespace Gnugat\Redaktilo\DependencyInjection;

class StaticContainer
{
    public static function makeEditor();

    public static function makeFilesystem();

    public static function makeReplaceEngine();
    public static function makeLineReplaceStrategy();

    public static function makeSearchEngine();
    public static function makeLineSearchStrategy();
    public static function makeLineNumberSearchStrategy();

    public static function makeLineContentConverter();
}
```

## FactoryMethod

Factory methods don't have any real behavior, their purpose is to make the code
easier to read by making things more literal.

### Filesystem2

`Editor` can open existing files. You can force it to open new files by passing
`true` as a second argument:

```php
$editor->open($filename, true);
```

This factory method allows you to make this argument more explicit:

```php
$editor->open($filename, Filesystem::forceCreation());
```

```php
<?php

namespace Gnugat\Redaktilo\FactoryMethod;

class Filesystem
{
    public static function forceCreation();
}
```

### Line

Somtimes you'll need to insert empty lines:

```php
$editor->addAfter($file, '');
```

This factory method allows you to make this more explicit:

```php
$editor->addAfter($file, Line::emptyOne());
```

```php
<?php

namespace Gnugat\Redaktilo\FactoryMethod;

class Line
{
    public static function emptyOne();
}
```

### LineNumber

You might want to jump a number of line above or under the current one:

```php
$editor->jumpUpTo($file, 3);
$editor->jumpDownTo($file, 5);
```

This factory method allows you to make this more explicit:

```php
$editor->jumpUpTo($file, LineNumber::up(3));
$editor->jumpDownTo($file, LineNumber::down(5));
```

You might also want to set the current line to a given line number:

```php
$file->setCurrentLineNumber(42);
```

Again, you cn make it more explicit:

```php
$file->setCurrentLineNumber(LineNumber::absolute(42));
```

`LineNumber` also normalizes the given parameter to make sure it is a positive
integer.

```php
<?php

namespace Gnugat\Redaktilo\Search\FactoryMethod;

class LineNumber
{
    public static function up($lines);
    public static function down($lines);

    public static function absolute($lineNumber);
}
```

## Search

Another stateless service, which allows you to search patterns in the File's
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

## Replace

Allows you to the replacements in the File's content.

This is actually an interface allowing you to extend Redaktilo. By default, one
implementation is provided.

```php
<?php

namespace Gnugat\Redaktilo\Replace;

use Gnugat\Redaktilo\File;

interface ReplaceStrategy
{
    public function replaceWith(File $file, $location, $replacement);
    public function removeAt(File $file, $location);
    public function insertAt(File $file, $location, $addition);

    public function supports($location);
}
```

### LineReplaceStrategy

Allows you to manipulate a line, givne its number.

## Engine

The strategies seen above can be gathered in an engine. This is used in the
`Editor` to allow extension without having to modify it.

For example, its `jumpDownTo` method can accept both a string or an integer.
It is passes its argument to the engine's `resolve` method: if the engine has
a registered strategy which supports it, it returns it. `Editor` can then tell
the strategy to do the work.

### SearchEngine

Allows you to register many `SearchStrategy` and to return the one that supports
the given pattern:

```php
<?php

namespace Gnugat\Redaktilo\Engine;

class SearchEngine
{
    public function registerStrategy(SearchStrategy $searchStrategy);
    public function resolve($pattern);
}
```

### ReplaceEngine

Allows you to register many `ReplaceStrategy` and to return the one that
supports the given location:

```php
<?php

namespace Gnugat\Redaktilo\Engine;

class ReplaceEngine
{
    public function registerStrategy(ReplaceStrategy $replaceStrategy);
    public function resolve($location);
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
