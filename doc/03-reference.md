# Architecture details

This chapter explains the responsibility of each classes:

* [Text](#text)
* [File](#file)
* [Factory](#factory)
    * [TextFactory](#textfactory)
    * [FileFactory](#filefactory)
* [Service](#service)
    * [LineBreak](#linebreak)
* [Filesystem](#filesystem)
* [Converter](#converter)
    * [PhpContentConverter](#phpcontentconverter)
* [DependencyInjection](#dependencyinjection)
* [Search](#search)
    * [LineNumberSearchStrategy](#linenumbersearchstrategy)
    * [LineRegexSearchStrategy](#lineregexsearchstrategy)
    * [LineSearchStrategy](#linesearchstrategy)
    * [PhpSearchStrategy](#phpsearchstrategy)
    * [SameSearchStrategy](#samesearchstrategy)
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

## Text

**Redaktilo** is based on this entity:

```php
<?php

namespace Gnugat\Redaktilo;

class Text
{
    public function __construct(array $lines, $lineBreak = PHP_EOL);

    public function getLines();
    public function setLines(array $lines);

    public function getLineBreak();
    public function setLineBreak($lineBreak);

    public function getCurrentLineNumber();
    public function setCurrentLineNumber($lineNumber);
}
```

Every single other classes in this project are stateless services allowing you
to manipulate it.

Basically it is a collection of lines: each line is stripped from their
line break (`Text` stores this character in a property).

A current line number is set to `0` when the `Text` is created:

```php
$text = new Text($lines, $lineBreak);
echo $text->getCurrentLineNumber(); // 0
```

## File

**Redaktilo** is also based on this entity:

```php
<?php

namespace Gnugat\Redaktilo;

class File extends Text
{
    public function getFilename();
    public function setFilename($filename);
}
```

As you can see, it extends the `Text` entity and adds a `filename` property:

```php
$file = new File($filename, $lines, $lineBreak);
```

## Factory

While these classes aren't extension points, they might be worth knowing.

### TextFactory

A stateless service which creates an instance of `Text` from the given string:

```php
<?php

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Service\LineBreak;

class TextFactory
{
    public function __construct(LineBreak $lineBreak);

    public function make($string);
}
```

Such a factory is usefull as it takes care of detecting the line break for you
(used to split the string into an array of lines).

### FileFactory

A stateless service which creates an instance of `File` from the given filename
and content:

```php
<?php

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Service\LineBreak;

class FileFactory
{
    public function __construct(LineBreak $lineBreak);

    public function make($filename, $content);
}
```

Such a factory is usefull as it takes care of detecting the line break for you.

## Service

Here lies the stateless services which are not meant to be extended.

### LineBreak

**Redaktilo** relies heavily on this service: a `Text` should be composed
of lines, but what line break character is used?

If the `Text` has been created on a Windows system, it should be `\r\n`. If it
has been  created elsewhere, it should be `\n`.

`LineBreak` helps you by returning the used line break character from the given
string:

```php
<?php

namespace Gnugat\Redaktilo\Service;

class LineBreak
{
    public function detect($string);
}
```

If the string doesn't contain any line break character, the current system's one
will be used (`PHP_EOL`).

## Filesystem

A service which does the actual read and write operations:

```php
<?php

namespace Gnugat\Redaktilo;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem
{
    public function __construct(SymfonyFilesystem $symfonyFilesystem);

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
different representations of the `Text`'s lines:

```php
<?php

namespace Gnugat\Redaktilo\Converter;

use Gnugat\Redaktilo\Text;

interface ContentConverter
{
    public function from(Text $text);
    public function back(Text $text, $convertedContent);
}
```

Possible representations might be:

* PHP tokens
* JSON parameters

### PhpContentConverter

This converter transform the content of a PHP source file into an array of tokens
via the `token_get_all()` function.

## Search

Another stateless service, which allows you to search patterns in the Text's
content.

This is actually an interface allowing you to extend Redaktilo. By default, two
implementations are provided.

```php
<?php

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Text;

interface SearchStrategy
{
    // Throw PatternNotFoundException if the pattern hasn't be found
    public function findAbove(Text $text, $pattern);
    public function findUnder(Text $text, $pattern);

    public function supports($pattern);
}
```

### LineNumberSearchStrategy

If you want to go to a given line number, use this one.

The `findUnder` method will jump `n` lines under the current one,  while
`findAbove` will jump above.

### LineRegexSearchStrategy

You can look for a line which matches a regex.

### LineSearchStrategy

This abstract class allows you to create search strategies which manipulate
array of lines.

Its `find` methods create a proper subset which can then be manipulated in
`findIn` implemntations.

### SameSearchStrategy

If you know exactly the value of the line you want to look for, use this one.

The `find` methods will return the line number.

### PhpSearchStrategy

If you want to manipulate a PHP file and jump to a line containing a set of
tokens, use this strategy.

### SearchEngine

The strategies seen above can be gathered in an search engine. This is used in
the `Editor` to allow extension without having to modify it.

For example, its `jumpUnder` method can accept both a string or an integer.
It is passes its argument to the engine's `resolve` method: if the engine has
a registered `SearchStrategy` which supports it, it returns it. `Editor` can
then tell the strategy to do the work.

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

Allows you to manipulate the Text's content.

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

The input parameter is currently an array with at least an entry `text` with the
text to manipulate.

### LineInsertAboveCommand

Inserts the given addition in the given text above the given location.

### LineInsertUnderCommand

Inserts the given addition in the given text under the given location.

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
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;

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
    // Filesystem operations.
    public function openFile($filename, $force = false); // Throws FileNotFoundException if the file hasn't be found
    public function saveFile(File $file); // Throws IOException if the file cannot be written to

    // In case you need to manipulate a string and not a file.
    public function openText($string);

    // Manipulating a line (by default the current one).
    public function insertAbove(Text $text, $addition, $location = null);
    public function insertUnder(Text $text, $addition, $location = null);
    public function replace(Text $text, $replacement, $location = null);
    public function remove(Text $text, $location = null); // Removes the current line.

    // Content navigation.
    // Throw PatternNotFoundException If the pattern hasn't been found
    // Throw NotSupportedException If the given pattern isn't supported by any registered strategy
    public function jumpUnder(Text $text, $pattern, $location = null);
    public function jumpAbove(Text $text, $pattern, $location = null);

    // Content searching.
    public function has(Text $text, $pattern); // Throws NotSupportedException If the given pattern isn't supported by any registered strategy
}
```

### Filesystem operations

While using `saveFile` is exactly the same as calling directly
`Filesystem::write`, the `openFile` method is a wrapper allowing you to open or
create files:

```php
$editor->openFile($filename); // Throws an exception if the file doesn't exist
$editor->openFile($filename, true); // Creates a new file if it doesn't exist
```

One last thing: opening or creating a file sets its cursor to the first line:

```php
$file = $this->openFile($filename);
echo $file->getCurrentLineNumber(); // 0
```

### Manipulating a line

You can insert additions above or under a given line (by default the current one).
Just keep in mind that the cursor will be set to the added line:

```php
$emptyLine = '';

echo $text->getCurrentLineNumber(); // 5
$editor->insertUnder($text, $emptyLine);
echo $text->getCurrentLineNumber(); // 6
```

You can also replace a line with a new value, or remove it.

### Content navigation

You can jump down or up to a line which correspond to the given pattern:

```php
$editor->jumpdUnder($text, 'The exact value of the line');
$editor->jumpdUnder($text, 2); // Jumps two lines under the current one.
```

You should keep in mind that the search is done relatively to the current one:

```php
$editor->jumpUnder($text, $linePresentAbove); // Will throw an exception.
```

If you don't want to start the search from the current line, you can indicate
the one you want:

```php
$editor->jumpAbove($text, $pattern, 42); // Starts from the 42th line
$editor->jumpUnder($text, $pattern, 0); // Starts from the top of the text
```

### Content searching

If you don't want to handle exceptions just to make sure that a line is present
in the text, use the following:

```php
$editor->has($text, $line);
```

## Next readings

* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
