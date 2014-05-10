# Usage

This chapter shows you how to use Redaktilo an contains the following sections:

* [API](#api)
* [Filesystem operations](#filesystem-operations)
* [Line insertion](#line-insertion)
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

    // Content navigation.
    public function jumpDownTo(File $file, $line);
    public function jumpUpTo(File $file, $line);
}
```

It's stateless, which means a single instance can be used in your whole
application.

Here's how to initialize it:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Editor;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

$symfonyFilesystem = new SymfonyFilesystem();
$filesystem = new Filesystem($symfonyFilesystem);
$editor = new Editor($filesystem);
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

## Line insertion

Redaktilo allows you to the insert new lines. This is done relatively to the
current line, either above or under:

```php
$firstLine = 'We are the knights';
$secondLine = 'Who say Ni!';

$editor->addBefore($file, $firstLine);
$editor->addAfter($file, $secondLine);
```

**Note**: after the insertion, the new line becomes the current one.

## Changing the current line

You can also change the current line to something else:

```php
$editor->changeTo($file, 'We are the knights!');
```

You can also use regular expressions to replace the current line. You can use
both a replace string and callback:

```php
$editor->replaceWith($file, '/Ni/', 'Peng');
$editor->replaceWith($file, '/(Ni|Peng)/', function ($matches) {
    return $matches[1] == 'Ni' ? 'Peng' : 'Ni';
});
```

## Removing the current line

Similarly, you can remove the current line:

```php
$editor->remove($file);
```

## Content navigation

You can change the current line by jumping to existing ones:

```php
$editor->jumpDownTo($file, 'And now for something completly different');
$editor->jumpUpTo($file, 'And now for something completly different');
```

If the file contains two lines similar, two calls are necessary to jump to the
second one.

Trying to jump to an inexistent line will result in an exception.

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
