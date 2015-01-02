# Redaktilo Code Reference

* [Editor API](#editor-api)
    * [Filesystem operations](#filesystem-operations)
    * [Content navigation](#content-navigation)
    * [Content manipulation](#content-manipulation)
    * [Commands](#commands)
* [Text API](#text-api)
    * [Side note on LineBreak](#side-note-on-linebreak)

## Editor API

The main stateless service:

```php
<?php

namespace Gnugat\Redaktilo;

class Editor
{
    public function open($filename, $force = false);
    public function save(File $file, $filename);

    // Throw Gnugat\Redaktilo\Exception\PatternNotFoundException
    public function jumpAbove(Text $text, $pattern, $location = null);
    public function jumpBelow(Text $text, $pattern, $location = null);
    public function hasBelow(Text $text, $pattern, $location = null);
    public function hasAbove(Text $text, $pattern, $location = null);

    public function insertAbove(Text $text, $addition, $location = null);
    public function insertBelow(Text $text, $addition, $location = null);
    public function replace(Text $text, $replacement, $location = null);
    public function replaceAll(Text $text, $pattern, $replacement);
    public function remove(Text $text, $location = null);

    public function run($name, array $input); // Throws Gnugat\Redaktilo\Exception\CommandNotFoundException
}
```

You can create one instance and use it everywhere:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
```

### Filesystem operations

Trying to open a non existing file will raise an exception, except if `true` is
passed as the second parameter. In any case nothing will happen on the
filesystem until the `save` method is called.

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
try {
    $file = $editor->open('/tmp/new.txt');
} catch (\Gnugat\Redaktilo\Exception\FileNotFoundException $e) {
    // The file doesn't exist
}
$file = $editor->open('/tmp/new.txt', true); // Forces file creation when it doesn't exist

// ... Make some manipulation on the file

$editor->save($file, '/tmp/new.txt'); // Actually writes on the filesystem
```

### Content navigation

`Editor` relies on `SearchEngine` in order to find a given pattern in a `Text`
and provides by default the following `SearchStrategies`:

* regular expression
* strict equality (`===`)

If the pattern isn't found, or if the pattern isn't supported by any strategies
an exception will be thrown. If the pattern is found, the `Text`'s current line
number will be set to it.

The search is done relatively to the current line (or, if the third argument is
given, to the given location): `jumpAbove` will start from it and then the line
above, etc until the top is reached while `jumpBelow` will go downward until the
bottom is reached.

In order to check the presence of a pattern without having to jump to the line
found, `hasAbove` and `hasBelow` methods can be used: it doesn't throw any
exceptions (checks from top to bottom).

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
$file = $editor->open('/tmp/life-of-brian.txt', true);
try {
    $editor->jumpAbove($file, '[A guard sniggers]'); // strict equality
} catch (\Gnugat\Redaktilo\Exception\NotSupportedException $e) {
    // The pattern isn't supported by any registered strategy (shouldn't occur often)
} catch (\Gnugat\Redaktilo\Exception\PatternNotFoundException $e) {
    // The pattern hasn't been found in the file
}
if ($editor->hasBelow($file, '/sniggers/', 0) { // regular expression
    // The pattern exists.
}
```

> **Note**: to jump to a given line number, you can directly use:
> `$text->setCurrentLineNumber($x);`.

### Content manipulation

Manipulations are done by default to the current line (or, if the third argument
is given, to the given location).

Inserting a new line will set the current one to it.

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$replace = function ($line) {
    return strtoupper($line);
};

$editor = EditorFactory::createEditor();
$spamMenu = $editor->open('/tmp/spam-menu.txt', true);
$editor->insertAbove($spamMenu, 'Egg'); // Current line number: 0
$editor->insertBelow($spamMenu, 'Bacon'); // Current line number: 1
$editor->replace($spamMenu, $replace);
$editor->replaceAll($spamMenu, '/*/', 'Spam');
$editor->remove($spamMenu);  // Current line number: 0

$editor->save($spamMenu, '/tmp/spam-menu.txt'); // Necessary to actually apply the changes on the filesystem
```

### Commands

You can define your own commands and use them through `Editor#run()`.

## Text API

One of the main entity:

```php
<?php

namespace Gnugat\Redaktilo;

class Text
{
    // factory methods
    public static function fromString($string);
    public static function fromArray(array $lines, $lineBreak = PHP_EOL);

    public function getLines();
    public function setLines(array $lines);
    public function getLength();

    public function getLineBreak();
    public function setLineBreak($lineBreak);

    public function map($callback);

    // Throws InvalidLineNumberException if $lineNumber is not a positive integer lower than the length
    public function setCurrentLineNumber($lineNumber);
    public function getCurrentLineNumber();
    public function incrementCurrentLineNumber($number);
    public function decrementCurrentLineNumber($number);

    // Throws InvalidLineNumberException if $lineNumber is not a positive integer lower than the length
    public function getLine($lineNumber = null);
    public function setLine($line, $lineNumber = null);
}
```

> **Important**: `lines` is an array of string stripped from their line break
> character.

If you need to manipulate a simple string you can use `Text#fromString`:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\Text;

$text = Text::fromString("why do witches burn?\n...because they're made of... wood?\n");
```

> **Important**: please note that upon creation, the current line number is
> initialized to the first line: `0` (array indexed).

### Side note on Line Breaks

A `StringUtil#detectLineBreak` method is used to find the line break, this is
done using the following rules:

* `\r\n` for windows
* `\n` for any other operating system
* `PHP_EOL` if no line ending has been found

## Next readings

* [Vocabulary](04-vocabulary.md)
* [Extending](05-extending.md)
* [Exceptions](06-exceptions.md)

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
