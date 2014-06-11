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

## Initialisation

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
$file = $editor->open($filename); // Current line: 'Bacon'
```

## Content navigation

A cursor has been set to the first line. You can move this cursor to any
existing lines:

```php
$editor->jumpDownTo($file, 'Egg'); // Current line: 'Egg'
```

As you can see, there's no need to add the line break character, `Editor` will
take care of it for you.

You should note that the lookup is directional:

```php
try {
    $editor->jumpDownTo($file, 'Bacon'); // Not found because 'Bacon' is above the current line
} catch (PatternNotFoundException $e) {
}
$editor->jumpUpTo($file, 'Bacon'); // Current line: 'Bacon'
```

To avoid handling exception if you just want to know if a line exists, use:

```php
$editor->has($file, 'Beans'); // false
```

You can also jump a wanted number of lines above or under the current one:

```php
$editor->jumpDownTo($file, FileNumber::down(2)); // Current line: Egg
$editor->jumpUpTo($file, FileNumber::up(2)); // Current line: Bacon
```

## Current line manipulation

You can insert new lines:

```php
$editor->addAfter($file, 'Spam'); // Line inserted after 'Bacon'. Current line: 'Spam'.
```

The insertion is also directional: you can either insert a new line before the
current one, or after it.

**Note**: once the insertion done, the cursor moves to the new line.

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
$editor->changeTo($file, 'Beans');
```

Or you can remove it:

```php
$editor->remove($file); // Current line: Egg
```

## Next readings

* [Use cases](doc/02-use-cases.md)
* [Reference](doc/03-reference.md)
* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
