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
This class contains all methods you need to open, navigate, edit and save files
or text. The editor doesn't have any state, this allows you to use a single
instance throughout the entire application while editing multiple different
files.

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

$file = $editor->open('monty-menu.txt');
```

This method returns a `File` instance. This object contains the content of the
file and keeps track of the cursor. When opening the file, the cursor is set
to line 0 ('Bacon').

In case the file does not exists, you can force the creation by passing `true`
as the second argument to `Editor#open()`.

### Navigating through the Content

A cursor has been set to the first line. You can move this cursor to any
existing line:

```php
// ...

$editor->jumpBelow($file, 'Egg'); // Current line: 1 ('Egg')
```

As you can see, there's no need to add the line break character, Redaktilo will
take care of it for you.

You should note that the lookup is directional:

```php
$editor->jumpBelow($file, 'Bacon'); // Throws \Gnugat\Redaktilo\Search\PatternNotFoundException, because 'Bacon' is above the current line

$editor->jumpAbove($file, 'Bacon'); // Current line: 0 ('Bacon')
```

The match is done only if the line value is exactly the same as the given one:

```php
$editor->jumpBelow($file, 'E'); // Throws an exception.
```

If you just want to know if a line exists, you don't have to deal with
exceptions, you can use the `Editor#has()` method instead:

```php
$editor->has($file, 'Beans'); // false
```

You can also jump to a number of lines below or above the current one:

```php
$editor->jumpBelow($file, 2); // Current line: 2 ('Sausage')
$editor->jumpAbove($file, 2); // Current line: 0 ('Bacon')
```

If you need to go the first occurence in the whole file (regardless of the
current line), you can use:

```php
// Jumps 1 line below the line 0
$editor->jumpBelow($file, 1, 0); // Current line: 1 (which is 'Egg')
```

The lookup can also be done using regex:

```php
$editor->jumpAbove($file, '/ac/'); // Current line: 0 (which is 'Bacon')
```

 > *Note*: If you're manipulating a PHP file, you can also jump to symbols like
 > class, methods and functions:

 > ```php
 > use Gnugat\Redaktilo\Search\Php\TokenBuilder;
 > // ...
 > 
 > $tokenBuilder = new TokenBuilder();
 > $registrationMethodName = 'registerBundles';
 > $registrationMethod = $tokenBuilder->buildMethod($registrationMethodName);

 > $editor->jumpBelow($file, $registrationMethod);
 > ```

### Manipulating a Line

Now you're able to navigate through a file and while that's very important in
order to edit a file, it doesn't help much if you can't manipulate lines.
Luckily, Redaktilo contains lots of methods designed for manipulating lines.

Using the `Editor#replace()` method, you can manipulate the current line:

```php
// ...

// ... navigate to the first line
$editor->replace($file, 'Spam'); // Line 0 now contains 'Spam' instead of 'Bacon'
```

You can also insert lines below or above the current line:

```php
// ...

$editor->insertAbove($file, 'Beans'); // inserts a line 'Beans' above Line 0
$editor->insertBelow($file, 'Bacon'); // inserts a line 'Bacon' below line 0
```

Please note that the cursor moves to the inserted line.

By default, all the manipulation methods work from the current line. If you would
like to manipulate a given line, you can pass its number as the third parameter:

```php
$editor->insertAbove($file, 'Spam', 23); // Line inserted above the line number 23.
```

At last, you can also delete lines:

```php
// ...
$editor->remove($file); // Removes the current line
```

### Saving the Modifications

For now the modification is only done in memory, to actually apply your changes
to the file you need to save it:

```php
// ...

$editor->saveFile($file);
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

## Previous readings

* [README](../README.md)
