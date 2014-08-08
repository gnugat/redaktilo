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
    public function __construct(Filesystem $filesystem);

    // Filesystem operations.
    public function open($filename, $force = false); // Throws FileNotFoundException if the file hasn't be found
    public function save(File $file); // Throws IOException if the file cannot be written to

    // Manipulating a line (by default the current one).
    public function insertAbove(File $file, $addition, $location = null);
    public function insertUnder(File $file, $addition, $location = null);
    public function replace(File $file, $replacement, $location = null); // Will be renamed to `replace`
    public function remove(File $file, $location = null); // Removes the current line.

    // Content navigation.
    // Throw PatternNotFoundException If the pattern hasn't been found
    // Throw NotSupportedException If the given pattern isn't supported by any registered strategy
    public function jumpDownTo(File $file, $pattern, $after = null);
    public function jumpUpTo(File $file, $pattern, $before = null);

    // Content searching.
    public function has(File $file, $pattern); // Throws NotSupportedException If the given pattern isn't supported by any registered strategy
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
$file = $editor->open($filename); // Current line: 0 (which is 'Bacon')
```

## Content navigation

A cursor has been set to the first line. You can move this cursor to any
existing lines:

```php
$editor->jumpDownTo($file, 'Egg'); // Current line: 1 (which is 'Egg')
```

As you can see, there's no need to add the line break character, `Editor` will
take care of it for you.

You should note that the lookup is directional:

```php
try {
    $editor->jumpDownTo($file, 'Bacon'); // Not found because 'Bacon' is above the current line
} catch (PatternNotFoundException $e) {
}
$editor->jumpUpTo($file, 'Bacon'); // Current line: 0 (which is 'Bacon')
```

The match is done only if the line value is exactly the same as the given one:

```php
$editor->jumpDownTo($file, 'B'); // Throws an exception.
```

To avoid handling exception if you just want to know if a line exists, use:

```php
$editor->has($file, 'Beans'); // false
```

You can also jump a wanted number of lines above or under the current one:

```php
$editor->jumpDownTo($file, 2); // Current line: 2 (which is 'Sausage')
$editor->jumpUpTo($file, 2); // Current line: 0 (which is 'Bacon')
```

If you need to go the first occurence in the whole file (regardless of the
current line), you can use:

```php
// Searches for the line number 1, starting the lookup from the first line (instead of the current one)
$editor->jumpDownTo($file, 1, 0); // Current line: 1 (which is 'Egg')
```

The lookup can also be done using regex:

```php
$editor->jumpUpTo($file, '/ac/'); // Current line: 0 (which is 'Bacon')
```

*Note*: If you're manipulating a PHP file, you can also jump to symbols like
class, methods and functions:

```php
use Gnugat\Redaktilo\Search\Php\TokenBuilder;

$registrationMethodName = 'registerBundles';
$registrationMethod = $tokenBuilder->buildMethod($registrationMethodName);

$editor->jumpDownTo($file, $registrationMethod);
```

## Line manipulation

By default, all the manipulation methods work with the current line. If you would
like to manipulate a given line, you can pass its number as the last parameter:

```php
$editor->insertAbove($file, 'Spam', 23); // Line inserted before the line number 23.
```

**Note**: once an operation done, the cursor moves to the line updated.

You can insert new lines:

```php
$editor->insertUnder($file, 'Spam'); // Line inserted after 'Bacon'. Current line: 'Spam'.
```

The insertion is also directional: you can either insert a new line before the
current one, or after it.

For now the modification is only done in memory, to actually apply your changes
to the file you need to save it:

```php
$editor->save($file);
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
