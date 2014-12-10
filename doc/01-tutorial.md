# Usage

This chapter shows you how to use Redaktilo and contains the following sections:

* [The Editor](#the-editor)
 * [Creating an Editor](#creating-an-editor)
 * [Customizing the Editor](#customizing-the-editor)
* [Editing Files](#editing-files)
 * [Navigating through the Content](#navigating-through-the-content)
 * [Manipulating a Line](#manipulating-a-line)
 * [Saving the Modifications](#saving-the-modifications)
* [Next readings](#next-readings)
* [Previous readings](#previous-readings)

## The Editor

The only class you'll be using when you use Redaktilo is the `Editor` class.
This class contains all methods you need to navigate and edit texts (it can also
open and save files).
The editor doesn't have any state, this allows you to use a single
instance throughout the entire application while editing multiple different
texts.

### Creating an Editor

The recommend way to instantiate the `Editor` is by using the `EditorFactory`:

```php
use Gnugat\Redaktilo\EditorFactory;

$editor = EditorFactory::createEditor();
```

This will create a default editor.

### Customizing the Editor

You can also use the editor builder to customize some things of the editor:

```php
// ...

$editor = EditorFactory::createEditorBuilder()
    // ... customize the build (more on this later)
    ->getEditor();
```

## Editing Files

Assume you have the following file:

    Bacon
    Egg
    Sausage

First things first: you need to open the file. This can be done easily with the
`Editor#open()` method:

```php
// ...

$montyMenu = $editor->open('monty-menu.txt');
```

This method returns a `Text` instance. This object contains the content of the
file and keeps track of the cursor. When opening the file, the cursor is set
to line 0 ('Bacon').

In case the file does not exists, you can force the creation by passing `true`
as the second argument to `Editor#open()`.

### Navigating through the Content

A cursor has been set to the first line. You can move this cursor to any
existing line:

```php
// ...

$editor->jumpBelow($montyMenu, 'Egg'); // Current line: 1 ('Egg')
```

As you can see, there's no need to add the line break character, Redaktilo will
take care of it for you.

You should note that the lookup is directional:

```php
$editor->jumpBelow($montyMenu, 'Bacon'); // Throws \Gnugat\Redaktilo\Exception\PatternNotFoundException, because 'Bacon' is above the current line

$editor->jumpAbove($montyMenu, 'Bacon'); // Current line: 0 ('Bacon')
```

The match is done only if the line value is exactly the same as the given one:

```php
$editor->jumpBelow($montyMenu, 'E'); // Throws an exception.
```

If you just want to know if a line exists, you don't have to deal with
exceptions, you can use `Editor#hasBelow()` and `Editor#hasAbove()` method instead:

```php
$editor->hasBelow($montyMenu, 'Beans', 0); // false
```

If you need to go to the first occurence in the whole file (regardless of the
current line), you can use:

```php
// Jumps to the first line matching the pattern, starting from the line 0
$editor->jumpBelow($montyMenu, '/eg/', 0); // Current line: 1 (which is 'Egg')
```

The lookup can also be done using regex:

```php
$editor->jumpAbove($montyMenu, '/ac/'); // Current line: 0 (which is 'Bacon')
```

### Manipulating a Line

Now you're able to navigate through a file and while that's very important in
order to edit a file, it doesn't help much if you can't manipulate lines.
Luckily, Redaktilo contains lots of methods designed for this purpose.

Using the `Text#setLine()` method, you can manipulate the current line:

```php
// ...

// ... navigate to the first line
$text->setLine('Spam'); // Line 0 now contains 'Spam' instead of 'Bacon'
```

 > *Note*: Editor provides a `replace` method which accepts callbacks:
 >
 > ```php
 > // Will be passed the given or current line
 > $replace = function ($line) {
 >     return strtoupper($line); // the line will be replaced by the returned value
 > };
 > $editor->replace($text, $replace, $location);
 > ```

You can also insert lines below or above the current line:

```php
// ...

$editor->insertAbove($montyMenu, 'Beans'); // inserts a line 'Beans' above Line 0
$editor->insertBelow($montyMenu, 'Bacon'); // inserts a line 'Bacon' below line 0
```

Please note that the cursor moves to the inserted line.

By default, all the manipulation methods work from the current line. If you would
like to manipulate a given line, you can pass its number as the third parameter:

```php
$editor->insertAbove($montyMenu, 'Spam', 23); // Line inserted above the line number 23.
```

At last, you can also delete lines:

```php
// ...
$editor->remove($montyMenu); // Removes the current line
```

### Saving the Modifications

For now the modification is only done in memory, to actually apply your changes
to the file you need to save it:

```php
// ...

$editor->save($montyMenu, 'monty-menu.txt');
```

The resulting file will be:

    Beans
    Spam
    Egg
    Sausage

## Next readings

* [Use cases](02-use-cases.md)
* [Reference](03-reference.md)
* [Vocabulary](04-vocabulary.md)
* [Extending](05-extending.md)
* [Exceptions](06-exceptions.md)

## Previous readings

* [README](../README.md)
