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
    * [Filesystem operations](#filesystem-operations)
    * [Manipulating the current line](#manipulating-the-current-line)
    * [Content navigation](#content-navigation)
    * [Content searching](#content-searching)
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

Currently the file also have some other methods:

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

One last thing: creating a `File` sets its cursor to the first line:

```php
$file = new File($filename, $content);
echo $file->getCurrentLineNumber(); // 0
```

## Filesystem

A service which does the actual read and write operations:

```php
<?php

namespace Gnugat\Redaktilo;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;

class Filesystem
{
    public function open($filename); // Throws FileNotFoundException if the file doesn't exist
    public function create($filename); // Throws IOException if the path isn't accessible

    public function exists($filename);

    public function write(File $file); // Throws IOException If the file cannot be written to
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

Sometimes you'll need to insert empty lines:

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

Again, you can make it more explicit:

```php
$file->setCurrentLineNumber(LineNumber::absolute(42));
```

`LineNumber` also normalizes the given parameter to make sure it is a positive
integer.

```php
<?php

namespace Gnugat\Redaktilo\FactoryMethod;

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

    // Throw PatternNotFoundException if the pattern hasn't be found
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
    public function resolve($pattern); // Throws NotSupportedException If the pattern isn't supported by any registered strategy
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
    public function resolve($location); // Throws NotSupportedException If the location isn't supported by any registered strategy
}
```

## EditorFactory

`EditorFactory` is the access point of Redaktilo. You should use it to create a
new Editor instance. You can also create an `EditorBuilder` instance to tweak
the instantiation of the editor.

```php
<?php

namespace Gnugat\Redaktilo;

class EditorFactory
{
    public static function createEditor();
    public static function createBuilder();
}
```

## EditorBuilder

Allows you to tweak the instantiation of the `Editor` class. It also has
defaults, in case you didn't specify anything. After configuring the build, you
can call `getEditor()` to get the `Editor` instance:

```php
<?php

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Engine\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use Gnugat\Redaktilo\Engine\ReplaceEngine;
use Gnugat\Redaktilo\Replace\ReplaceStrategy;
use Gnugat\Redaktilo\Converter\ContentConverter;
use Gnugat\Redaktilo\Converter\LineContentConverter;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class EditorBuilder
{
    public function getEditor();

    public function setSearchEngine(SearchEngine $searchEngine);
    public function addSearchStrategy(SearchStrategy $searchStrategy);

    public function addReplaceStrategy(ReplaceStrategy $replaceStrategy);
    public function setReplaceEngine(ReplaceEngine $replaceEngine);

    public function setFilesystem(Filesystem $filesystem);
}
```

## Editor

`Editor` is intended to be a facade using every other services. It provides
developers with a unique API implementing the text editor metaphor:

```php
<?php

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Engine\NotSupportedException;
use Gnugat\Redaktilo\Search\PatternNotFoundException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;

class Editor
{
    public function __construct(Filesystem $filesystem);

    // Filesystem operations.
    public function open($filename, $force = false); // Throws FileNotFoundException if the file hasn't be found
    public function save(File $file); // Throws IOException if the file cannot be written to

    // Manipulating the current line.
    public function addBefore(File $file, $addition);
    public function addAfter(File $file, $addition);
    public function changeTo(File $file, $replacement); // Will be renamed to `replace`
    public function remove(File $file); // Removes the current line.

    // Global manipulations.
    public function replaceWith(File $file, $regex, $replacement); // Will be renamed to `replaceAll`

    // Content navigation.
    // Throw PatternNotFoundException If the pattern hasn't been found
    // Throw NotSupportedException If the given pattern isn't supported by any registered strategy
    public function jumpDownTo(File $file, $pattern);
    public function jumpUpTo(File $file, $pattern);

    // Content searching.
    public function has(File $file, $pattern); // Throws NotSupportedException If the given pattern isn't supported by any registered strategy
}
```

### Filesystem operations

While using `save` is exactly the same as calling directly `Filesystem::write`,
the `open` method is a wrapper allowing you to open or create files:

```php
$editor->open($filename); // Throws an exception if the file doesn't exist
$editor->open($filename, true); // Creates a new file if it doesn't exist
```

If you want to make the second argument more explicit, use the following factory
method:

```php
use Gnugat\Redaktilo\FactoryMethod\Filesystem;

$editor->open($filename, Filesystem::forceCreation());
```

One last thing: opening or creating a file sets its cursor to the first line:

```php
$file = $this->open($filename);
echo $file->getCurrentLineNumber(); // 0
```

### Manipulating the current line

You can insert additions above or under the current line. Just keep in mind that
the cursor will be set to the added line:

```php
use Gnugat\Redaktilo\FactoryMethod\Line;

{
echo $file->getCurrentLineNumber(); // 5
$editor->addAfter($file, Line::emptyOne());
echo $file->getCurrentLineNumber(); // 6
```

You can also replace the current line with a new value, or remove it.

### Content navigation

You can jump down or up to a line which correspond to the given pattern:

```php
use Gnugat\Redaktilo\FactoryMethod\LineNumber

$editor->jumpdDownTo($file, 'The exact value of the line');
$editor->jumpdDownTo($file, LineNumber::down(2)); // Jumps two lines under the current one.
```

You should keep in mind that the search is done relatively to the current one:

```php
$editor->jumpDownTo($file, $linePresentAbove); // Will throw an exception.
```

### Content searching

If you don't want to handle exceptions just to make sure that a line is present
in the file, use the following:

```php
$editor->has($file, $line);
```

## Next readings

* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
* [Tutorial](doc/01-tutorial.md)
* [Use cases](doc/02-use-cases.md)
