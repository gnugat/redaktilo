# Usage

This chapter shows you how to use Redaktilo an contains the following sections:

* [API](#api)
* [Filesystem operations](#filesystem-operations)
* [Line manipulation](#line-manipulation)
  * [insertion](#insertion)
  * [replacement](#replacement)
  * [removal](#removal)
* [Content navigation](#content-navigation)
* [Example](#example)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## API

Redaktilo has been designed to be used uniquely via the following class:

```php
<?php

namespace Gnugat\Redaktilo;

class Editor
{
    public function __construct(Filesystem $filesystem);

    // Filesystem operations.
    public function open($filename, $force = false);
    public function save(File $file);

    // Line manipulations.
    public function addBefore(File $file, $add);
    public function addAfter(File $file, $add);
    public function changeTo(File $file, $line);
    public function replaceWith(File $file, $regex, $replace);
    public function remove(File $file); // Removes the current line.

    // Content searching.
    public function jumpDownTo(File $file, $pattern);
    public function jumpUpTo(File $file, $pattern);
    public function has(File $file, $pattern);
}
```

It's stateless, which means a single instance can be used in your whole
application.

Here's how to initialize it:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\LineNumberSearchStrategy;
use Gnugat\Redaktilo\Search\LineSearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

$searchEngine = new SearchEngine();

$lineSearchStrategy = new LineSearchStrategy();
$searchEngine->registerStrategy($lineSearchStrategy);

$lineNumberSearchStrategy = new LineNumberSearchStrategy();
$searchEngine->registerStrategy($lineNumberSearchStrategy);

$symfonyFilesystem = new SymfonyFilesystem();
$filesystem = new Filesystem($symfonyFilesystem);
$editor = new Editor($filesystem, $searchEngine);
```

## Filesystem operations

In order to manipulate a file, you must open it:

```php
$file = $editor->open('/tmp/monty.py');
```

By default, if the file doesn't exist an exception will be thrown
(`Symfony\Component\Filesystem\Exception\FileNotFoundException`). You need to
pass the following argument to force a file creation:

```php
$file = $editor->open('/tmp/monty.py', true);
```

Any changes are done in memory, if you want to apply them you need to save the
file:

```php
$editor->save($file);
```

**Note 1**: newly created files using the force option won't be present on the
filesystem as long as you don't call the save method.

**Note 2**: when opening a file, the current line is set to the first one.

## Line manipulation

Redaktilo allows you to manipulate the lines of the opened File, relatively to
the line currently selected.

### Insertion

New lines can be inserted above or under the current one:

```php
$firstLine = 'We are the knights';
$secondLine = 'Who say Ni!';

$editor->addBefore($file, $firstLine);
$editor->addAfter($file, $secondLine);
```

**Note**: after the insertion, the new line becomes the current one.

### Replacement

The current line can also be replaced entirely:

```php
$editor->changeTo($file, 'We are the knights!');
```

Or you can replace  a small portion of the current line using regular
expressions:

```php
$editor->replaceWith($file, '/Ni/', 'Peng');
$editor->replaceWith($file, '/(Ni|Peng)/', function ($matches) {
    return $matches[1] == 'Ni' ? 'Peng' : 'Ni';
});
```

### Removal

Finally, the current line can be removed from the file:

```php
$editor->remove($file);
```

## Content searching

You can change the current line by jumping to existing ones.

The jump can be done in two directions, but trying to jump to an inexistent line
will result in an exception
(`Symfony\Component\Filesystem\Exception\FileNotFoundException`).

### Exact line search

You can pass the line where you want to jump to (if you know its exact value):

```php
$editor->jumpDownTo($file, 'Existing line under the current one');
$editor->jumpUpTo($file, 'Existing line above the current one');
```

If the file contains two lines similar, two calls are necessary to jump to the
second one.

To check the presence of a line in the file, use this:

```php
$editor->has($file, 'I came here to learn how to fly an airplane');
```

### Go to a line number

Going to a line number can be done directly with `File`:

```php
$file->setCurrentLineNumber(LineNumber::absolute(42));
```

`Editor` provides you with a way to check if the line you're about to set is
within the file's range:

```php
$editor->has($file, LineNumber::absolute(42));
```

If you just want to jump 2 lines above the current one:

```php
$editor->jumpUpTo($file, LineNumber::up(2));
```

Or if you want to jump 5 lines under the current one:

```php
$editor->jumpDownTo($file, LineNumber::down(5));
```

**Note**: `Gnugat\Redaktilo\Search\FactoryMethod\LineNumber` returns the same
number you passed to its method. Use it to make your code easier to read.

## Example

Let's consider the following file:

    Bacon
    Egg
    Sausage

When opening a file, the cursor is set to the first line:

```php
$filename = '/tmp/monty-menu.txt';
$file = $editor->open($filename); // Current line: 'Bacon'
```

You can move the cursor to any existing lines:

```php
$editor->jumpDownTo($file, 'Egg'); // Current line: 'Egg'
```

As you can see, there's no need to add the newline character, `LineEditor` will
do it for you.
The lookup is directional:

```php
$editor->jumpDownTo($file, 'Bacon'); // Not found because 'Bacon' is above the current line
$editor->jumpUpTo($file, 'Bacon'); // Current line: 'Bacon'
```

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

## Next readings

* [Use cases](doc/02-use-cases.md)
* [Architecture details](doc/03-architecture-details.md)
* [Vocabulary](04-vocabulary.md)

## Previous readings

* [README](../README.md)
