# Architecture details

This chapter explains the responsibility of each classes:

* [File](#file)
* [Filesystem](#filesystem)
* [Converter](#converter)
    * [LineContentConverter](#linecontentconverter)
    * [PhpContentConverter](#phpcontentconverter)
* [DependencyInjection](#dependencyinjection)
* [Search](#search)
    * [LineNumberSearchStrategy](#linenumbersearchstrategy)
    * [LineRegexSearchStrategy](#lineregexsearchstrategy)
    * [LineSearchStrategy](#linesearchstrategy)
    * [PhpSearchStrategy](#phpsearchstrategy)
    * [SameSearchStrategy](#samesearchstrategy)
    * [SubstringSearchStrategy](#substringsearchstrategy)
    * [SearchEngine](#searchengine)
* [Command](#command)
    * [LineInsertCommand](#lineinsertcommand)
    * [LineReplaceCommand](#linereplacecommand)
    * [LineRemoveCommand](#lineremovecommand)
    * [CommandInvoker](#commandinvoker)
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

### PhpContentConverter

This converter transform the content of a PHP source file into an array of tokens
via the `token_get_all()` function.

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

### LineNumberSearchStrategy

If you want to go to a given line number, use this one.

The `findNext` method will jump `n` lines under the current one,  while
`findPrevious` will jump above.

The `has` method just checks that the given line number is within the boundary
of the file.

### LineRegexSearchStrategy

You can look for a line which matches a regex.

### LineSearchStrategy

This abstract class allows you to create search strategies which manipulate
array of lines.

Its `find` methods create a proper subset which can then be manipulated in
`findIn` implemntations.

### SameSearchStrategy

If you know exactly the value of the line you want to look for, use this one.

The `has` method will look in the whole file and will return `true` if at least
one line matches exactly the given one.

The `find` methods will return the line number.

### PhpSearchStrategy

If you want to manipulate a PHP file and jump to a line containing a set of
tokens, use this strategy.

### SubstringSearchStrategy

This strategy looks if the given string is contained in each lines.

### SearchEngine

The strategies seen above can be gathered in an search engine. This is used in
the `Editor` to allow extension without having to modify it.

For example, its `jumpDownTo` method can accept both a string or an integer.
It is passes its argument to the engine's `resolve` method: if the engine has
a registered `SearchStrategy` which supports it, it returns it. `Editor` can then tell
the strategy to do the work.

```php
<?php

namespace Gnugat\Redaktilo\Search;

class SearchEngine
{
    public function registerStrategy(SearchStrategy $searchStrategy);
    public function resolve($pattern); // Throws NotSupportedException If the pattern isn't supported by any registered strategy
}
```

## Command

Allows you to manipulate the File's content.

This is actually an interface allowing you to extend Redaktilo. By default, three
implementations are provided.

```php
<?php

namespace Gnugat\Redaktilo\Command;

interface Command
{
    public function getName();
    public function execute(array $input);
}
```

The input parameter is currently an array with at least an entry `file` with the
file to manipulate.

### LineInsertCommand

Allows you to insert a line at the given location.

### LineReplaceCommand

Allows you to replace a line, given its number.

### LineRemoveCommand

Allows you to remove a line, given its number.

### CommandInvoker

The commands seen above can be gathered in a command invoker. This is used in
the `Editor` to allow extension without having to modify it.

The `run` method - called by manipulating methods of the `Editor` - accept in first
argument the name to resolve the correct command to execute and in second
argument the `$input` array to send to the command

```php
<?php

namespace Gnugat\Redaktilo\Command;

class CommandInvoker
{
    public function addCommand(Command $command);
    public function run($name, array $input); // Throws UnsupportedCommandException if the name doesn't correspond to any registered command
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

use Gnugat\Redaktilo\Command\Command;
use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\Converter\ContentConverter;
use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class EditorBuilder
{
    public function getEditor();

    public function setSearchEngine(SearchEngine $searchEngine);
    public function addSearchStrategy(SearchStrategy $searchStrategy);

    public function setCommandInvoker(CommandInvoker $commandInvoker);
    public function addCommand(Command $command);

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

    // Manipulating a line (by default the current one).
    public function addBefore(File $file, $addition, $location=null);
    public function addAfter(File $file, $addition, $location=null);
    public function changeTo(File $file, $replacement, $location=null); // Will be renamed to `replace`
    public function remove(File $file, $location=null); // Removes the current line.

    // Global manipulations.
    public function replaceWith(File $file, $regex, $replacement, $location=null); // Will be renamed to `replaceAll`

    // Content navigation.
    // Throw PatternNotFoundException If the pattern hasn't been found
    // Throw NotSupportedException If the given pattern isn't supported by any registered strategy
    public function jumpTo(File $file, $pattern);
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

One last thing: opening or creating a file sets its cursor to the first line:

```php
$file = $this->open($filename);
echo $file->getCurrentLineNumber(); // 0
```

### Manipulating a line

You can insert additions above or under a given line (by default the current one).
Just keep in mind that the cursor will be set to the added line:

```php
$emptyLine = '';

echo $file->getCurrentLineNumber(); // 5
$editor->addAfter($file, $emptyLine);
echo $file->getCurrentLineNumber(); // 6
```

You can also replace a line with a new value, or remove it.

### Content navigation

You can jump down or up to a line which correspond to the given pattern:

```php
$editor->jumpdDownTo($file, 'The exact value of the line');
$editor->jumpdDownTo($file, 2); // Jumps two lines under the current one.
```

You should keep in mind that the search is done relatively to the current one:

```php
$editor->jumpDownTo($file, $linePresentAbove); // Will throw an exception.
```

The `jumpTo` method allows you to find the first occurence in the file (unlike
the other jump methods it doesn't care about the current line).
This is particularly usefull if you want to jump to an absolute line number.

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
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
