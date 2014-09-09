# Usage

This chapter shows you how to use Redaktilo and contains the following sections:

* [API](#api)
* [Initialisation](#initialisation)
* [Content navigation](#content-navigation)
* [Current line manipulation](#current-line-manipulation)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## API

There's only one class to know in Redaktilo, the `Editor`:

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
    public function open($filename, $force = false); // Throws FileNotFoundException if the file hasn't be found
    public function save(File $file); // Throws IOException if the file cannot be written to

    // Manipulating a line (by default the current one).
    public function insertAbove(Text $text, $addition, $location = null);
    public function insertBelow(Text $text, $addition, $location = null);
    public function replace(Text $text, $replacement, $location = null);
    public function remove(Text $text, $location = null); // Removes the current line.

    // Content navigation.
    // Throw PatternNotFoundException If the pattern hasn't been found
    // Throw NotSupportedException If the given pattern isn't supported by any registered strategy
    public function jumpAbove(Text $text, $pattern, $location = null);
    public function jumpBelow(Text $text, $pattern, $location = null);

    // Content searching.
    public function has(Text $text, $pattern); // Throws NotSupportedException If the given pattern isn't supported by any registered strategy
}
```

It doesn't have any state, so you can use a single instance for your entire
application.

## Initialization

In order to create an instance of `Editor`, you can use the following factory:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
```

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
$editor->jumpBelow($file, 'Egg'); // Current line: 1 (which is 'Egg')
```

As you can see, there's no need to add the line break character, Redaktilo will
take care of it for you.

You should note that the lookup is directional:

```php
try {
    $editor->jumpBelow($file, 'Bacon'); // Not found because 'Bacon' is above the current line
} catch (\Gnugat\Redaktilo\Search\PatternNotFoundException $e) {
}
$editor->jumpAbove($file, 'Bacon'); // Current line: 0 (which is 'Bacon')
```

The match is done only if the line value is exactly the same as the given one:

```php
$editor->jumpBelow($file, 'E'); // Throws an exception.
```

If you just want to know if a line exists, you don't have to deal with
exceptions:

```php
$editor->has($file, 'Beans'); // false
```

If you need to go the first occurence in the whole file (regardless of the
current line), you can use:

```php
// Jumps to the first line matching the pattern, starting from the line 0
$editor->jumpBelow($file, '/eg/, 0); // Current line: 1 (which is 'Egg')
```

The lookup can also be done using regex:

```php
$editor->jumpAbove($file, '/ac/'); // Current line: 0 (which is 'Bacon')
```

*Note*: You can directly interact with the current line:

```php
$file->setCurrentLineNumber(2); // Current line: 2 (which is 'Sausage')
```

*Note*: If you're manipulating a PHP file, you can also jump to symbols like
class, methods and functions:

```php
use Gnugat\Redaktilo\Search\Php\TokenBuilder;

$registrationMethodName = 'registerBundles';
$registrationMethod = $tokenBuilder->buildMethod($registrationMethodName);

$editor->jumpBelow($file, $registrationMethod);
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
$editor->insertBelow($file, 'Spam'); // Line inserted below 'Bacon'. Current line: 'Spam'.
```

The insertion is also directional: you can either insert a new line above the
current one, or below it.

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
* [Extending](05-extending.md)

## Previous readings

* [README](../README.md)
