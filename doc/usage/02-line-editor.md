# LineEditor usage

If you need to manipulate lines, then `LineEditor` is what you're looking for.

Here's how to initialize it:

```php
use Gnugat\Redaktilo\File\Filesystem;
use Gnugat\Redaktilo\Editor\LineEditor;

$filesystem = new Filesystem();
$editor = new LineEditor($filesystem);
```

Let's consider the following file:

    Bacon
    Egg
    Sausage

When opening a file, the cursor is set to the first line:

```php
$filename = '/tmp/menu.txt';
$editor->open($filename); // Current line: 'Bacon'
```

You can move the cursor to any existing lines:

```php
$editor->jumpDownTo('Egg'); // Current line: 'Egg'
```

As you can see, there's no need to add the newline character, `LineEditor` will
do it for you.
The lookup is directional:

```php
$editor->jumpDownTo('Bacon'); // Not found because 'Bacon' is above the current line
$editor->jumpUpTo('Bacon'); // Current line: 'Bacon'
```

You can insert new lines:

```php
$editor->addAfter('Spam'); // Line inserted after 'Bacon'. Current line: 'Spam'.
```

The insertion is also directional: you can either insert a new line before the
current one, or after it.

**Note**: once the insertion done, the cursor moves to the new line.

For now the modification is only done in memory, to actually apply your changes
to the file you need to save it:

```php
$editor->save();
```

The resulting file will be:

    Bacon
    Spam
    Egg
    Sausage

## Advised readings

* [Usage introduction](01-introduction.md)
* [Use cases introduction](../use-cases/01-introduction.md)
* [YAML configuration edition](../use-cases/02-yaml-configuration-edition.md)
* [JSON configuration edition](../use-cases/03-json-configuration-edition.md)
* [PHP source code edition](../use-cases/04-php-source-code-edition.md)
* [Global introduction](../01-introduction.md)
