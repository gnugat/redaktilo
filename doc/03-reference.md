# Redaktilo Code Reference

* [Editor API](#editor-api)
    * [Filesystem operations](#filesystem-operations)
    * [Content navigation](#content-navigation)
    * [Content manipulation](#content-manipulation)
    * [Commands](#commands)
        * [Jump Relatively](#jump-relatively)
        * [Jump to a Percentage](#jump-to-a-percentage)
* [Text API](#text-api)
    * [Side note on LineBreak](#side-note-on-linebreak)
* [File API](#file-api)

## Editor API

The main stateless service:

```php
<?php

namespace Gnugat\Redaktilo;

class Editor
{
    public function open($filename, $force = false);
    public function save(File $file);

    // Throw Gnugat\Redaktilo\Search\PatternNotFoundException
    public function jumpAbove(Text $text, $pattern, $location = null);
    public function jumpBelow(Text $text, $pattern, $location = null);
    public function hasBelow(Text $text, $pattern, $location = null);
    public function hasAbove(Text $text, $pattern, $location = null);

    public function insertAbove(Text $text, $addition, $location = null);
    public function insertBelow(Text $text, $addition, $location = null);
    public function replace(Text $text, $replacement, $location = null);
    public function remove(Text $text, $location = null);

    public function run($name, array $input); // Throws Gnugat\Redaktilo\Command\CommandNotFoundException
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
} catch (\Symfony\Component\Filesystem\Exception\FileNotFoundException $e) {
    // The file doesn't exist
}
$file = $editor->open('/tmp/new.txt', true); // Forces file creation when it doesn't exist

// ... Make some manipulation on the file

$editor->save($file); // Actually writes on the filesystem
```

### Content navigation

`Editor` relies on `SearchEngine` in order to find a given pattern in a `Text`
and provides by default the following `SearchStrategies`:

* regular expression
* strict equality (`===`)
* PHP token

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
} catch (\Gnugat\Redaktilo\Search\NotSupportedException $e) {
    // The pattern isn't supported by any registered strategy (shouldn't occur often)
} catch (\Gnugat\Redaktilo\Search\PatternNotFoundException $e) {
    // The pattern hasn't been found in the file
}
if ($editor->hasBelow($file, '/sniggers/', 0) { // regular expression
    // The pattern exists.
}
```

**Note**: to jump to a given line number, you can directly use:
`$text->setCurrentLineNumber($x);`.

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
$file = $editor->open('/tmp/spam-menu.txt', true);
$editor->insertAbove($file, 'Egg'); // Current line number: 0
$editor->insertBelow($file, 'Bacon'); // Current line number: 1
$editor->replace($file, $replace);
$editor->remove($file);  // Current line number: 0

$editor->save($file); // Necessary to actually apply the changes on the filesystem
```

### Commands

More content manipulation are available through `Editor#run()`.

#### Jump Relatively

The commands `jump_above` and `jump_below` take the same arguments and provide
a way to jump a given number of lines above or below the current one.

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
$file = $editor->open('/tmp/spam-menu.txt', true);
$file->getCurrentLineNumber(); // 0

$editor->run('jump_below', array('text' => $file, 'number' => 4)); // Jumps 4 lines below
$file->getCurrentLineNumber(); // 4

$editor->run('jump_below', array('text' => $file)); // If no number specified, 1 is assumed
$file->getCurrentLineNumber(); // 5

$editor->run('jump_above', array('text' => $file, 'number' => 2)); // Jumps 2 lines above
$file->getCurrentLineNumber(); // 3

$editor->run('jump_above', array('text' => $file)); // If no number specified, 1 is assumed
$file->getCurrentLineNumber(); // 2
```

#### Jump to a Percentage

The command `jump_percent` provides a way to jump absolutely to a given
percentage.

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
$file = $editor->open('/tmp/spam-menu.txt', true);
$file->getLength(); // 10

$editor->run('jump_percent', array('text' => $file, 'number' => 50)); // Jump to the middle of the text (50%)
$file->getCurrentLineNumber(); // 5

$editor->run('jump_percent', array('text' => $file, 'number' => 0)); // Jump to the top of the text (0%)
$file->getCurrentLineNumber(); // 0

$editor->run('jump_percent', array('text' => $file)); // If no number specified, 100 is assumed (the bottom of the text, 100%)
$file->getCurrentLineNumber(); // 9
```

**Note**: giving a negative number or a number superior to 100 will raise a
`\InvalidArgumentException`.

## Text API

One of the main entity:

```php
<?php

namespace Gnugat\Redaktilo;

class Text
{
    public function getLines();
    public function setLines(array $lines);
    public function getLength();

    public function getLineBreak();
    public function setLineBreak($lineBreak);

    // Throws InvalidLineNumberException if $lineNumber is not a positive integer lower than the length
    public function setCurrentLineNumber($lineNumber);
    public function getCurrentLineNumber();


    // Throws InvalidLineNumberException if $lineNumber is not a positive integer lower than the length
    public function getLine($lineNumber = null);
    public function setLine($line, $lineNumber = null);
}
```

**Important**: `lines` is an array of string stripped from their line break
character.

The `Editor` is a bit `File` oriented, but if you want to manipulate a simple
string you can use `Text`:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\Service\LineBreak;
use Gnugat\Redaktilo\Service\TextFactory;

$textFactory = new TextFactory(new LineBreak());
$text = $textFactory->make("why do witches burn?\n...because they're made of... wood?\n");
```

**Important**: please note that upon creation, the current line number is
initialized to the first line: `0` (array indexed).

### Side note on LineBreak

The `LineBreak` stateless service will guess the right character used to
separate lines:

* `\r\n` for windows
* `\n` for any other operating system
* `PHP_EOL` if no line ending has been found

## File API

The other main entity:

```php
<?php

namespace Gnugat\Redaktilo;

class File extends Text
{
    public function getFilename();
    public function setFilename($filename);

    // ... (Text methods)
}
```

The best way to create it is to use the `Editor`:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
$file = $editor->open('/tmp/and-now-for-something-completly-different.txt');
// ... Edit the file
$editor->save($file); // Actually writes on the filesystem
```

## Next readings

* [Vocabulary](04-vocabulary.md)
* [Extending](05-extending.md)
* [Exceptions](06-exceptions.md)

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
