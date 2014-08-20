# Usage

This chapter shows you how to use Redaktilo and contains the following sections:

* [API](#api)
* [Initialisation](#initialisation)
* [Content navigation](#content-navigation)
* [Current line manipulation](#current-line-manipulation)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## API

Redaktilo has been designed to be used uniquely via the following class:

```php
<?php

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Search\NotSupportedException;
use Gnugat\Redaktilo\Search\PatternNotFoundException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;

class Editor
{
    // Filesystem operations.
    public function openFile($filename, $force = false); // Throws FileNotFoundException if the file hasn't be found
    public function saveFile(File $file); // Throws IOException if the file cannot be written to

    // Manipulating a line (by default the current one).
    public function insertAbove(Text $text, $addition, $location = null);
    public function insertUnder(Text $text, $addition, $location = null);
    public function replace(Text $text, $replacement, $location = null);
    public function remove(Text $text, $location = null); // Removes the current line.

    // Content navigation.
    // Throw PatternNotFoundException If the pattern hasn't been found
    // Throw NotSupportedException If the given pattern isn't supported by any registered strategy
    public function jumpAbove(Text $text, $pattern, $location = null);
    public function jumpUnder(Text $text, $pattern, $location = null);

    // Content searching.
    public function has(Text $text, $pattern); // Throws NotSupportedException If the given pattern isn't supported by any registered strategy
}
```

## Initialization

In order to manipulate a file, you need to create an instance of `Editor`:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
```

This is the only class you need to manipulate and its stateless: you can use
the same instance in your whole application.

Let's consider the following file:

    Bacon
    Egg
    Sausage

First things first: you need to open the file:

```php
$filename = '/tmp/monty-menu.txt';
$file = $editor->openFile($filename); // Current line: 0 (which is 'Bacon')
```

## Content navigation

A cursor has been set to the first line. You can move this cursor to any
existing lines:

```php
$editor->jumpUnder($file, 'Egg'); // Current line: 1 (which is 'Egg')
```

As you can see, there's no need to add the line break character, `Editor` will
take care of it for you.

You should note that the lookup is directional:

```php
try {
    $editor->jumpUnder($file, 'Bacon'); // Not found because 'Bacon' is above the current line
} catch (PatternNotFoundException $e) {
}
$editor->jumpAbove($file, 'Bacon'); // Current line: 0 (which is 'Bacon')
```

The match is done only if the line value is exactly the same as the given one:

```php
$editor->jumpUnder($file, 'B'); // Throws an exception.
```

To avoid handling exception if you just want to know if a line exists, use:

```php
$editor->has($file, 'Beans'); // false
```

You can also jump a wanted number of lines above or under the current one:

```php
$editor->jumpUnder($file, 2); // Current line: 2 (which is 'Sausage')
$editor->jumpAbove($file, 2); // Current line: 0 (which is 'Bacon')
```

If you need to go the first occurence in the whole file (regardless of the
current line), you can use:

```php
// Searches for the line number 1, starting the lookup from the first line (instead of the current one)
$editor->jumpUnder($file, 1, 0); // Current line: 1 (which is 'Egg')
```

The lookup can also be done using regex:

```php
$editor->jumpAbove($file, '/ac/'); // Current line: 0 (which is 'Bacon')
```

*Note*: If you're manipulating a PHP file, you can also jump to symbols like
class, methods and functions:

```php
use Gnugat\Redaktilo\Search\Php\TokenBuilder;

$registrationMethodName = 'registerBundles';
$registrationMethod = $tokenBuilder->buildMethod($registrationMethodName);

$editor->jumpUnder($file, $registrationMethod);
```

## Line manipulation

By default, all the manipulation methods work with the current line. If you would
like to manipulate a given line, you can pass its number as the last parameter:

```php
$editor->insertAbove($file, 'Spam', 23); // Line inserted above the line number 23.
```

**Note**: once an operation done, the cursor moves to the line updated.

You can insert new lines:

```php
$editor->insertUnder($file, 'Spam'); // Line inserted under 'Bacon'. Current line: 'Spam'.
```

The insertion is also directional: you can either insert a new line above the
current one, or under it.

For now the modification is only done in memory, to actually apply your changes
to the file you need to save it:

```php
$editor->saveFile($file);
```

The resulting file will be:

    Bacon
    Spam
    Egg
    Sausage

Of course you can replace the line entirely:

```php
$editor->replace($file, 'Beans');
```

Or you can remove it:

```php
$editor->remove($file); // Current line: Egg
```

Those two methods also accept a location argument if you don't want to use the
current line number.

## Next readings

* [Use cases](02-use-cases.md)
* [Reference](03-reference.md)
* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
