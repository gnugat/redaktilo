# Vocabulary

* [Actions](#actions)
    * [insert](#insert)
    * [remove](#remove)
    * [replace](#replace)
* [Cursor](#cursor)
* [Directions](#directions)
    * [above](#above)
    * [below](#below)
* [Editor](#editor)
* [File](#file)
* [Line](#line)
* [Location](#location)
* [Redaktilo](#redaktilo)
* [Text](#text)

## Actions

Here's the vocabulary for the possible actions on a line.

### insert

Should be prefered over the word `add`.

### remove

Should be prefered over the word `delete`.

### replace

Should be prefered over the word `change`.

## Cursor

Also used to mean the current line.

An indicator used to know the position in the text. Currently a line number is
used, but it could change to:

* a line and a column
* a token's place in an array

This is useful when a pattern occurs many times in the text: it enables the
editor to select the wanted one.

The cursor also enables to manipulate the selected element.

## Directions

Here's the vocabulary to locate something relatively in a collection of lines.

### above

Used when doing something.

Should be prefered over the words `over`, `before`, `previous` or `up`.

### below

Used when doing something.

Should be prefered over the words `under`, `after`, `next` or `down`.

## Editor

Also called "text editor".

A piece of software which is able to change the content of a text.
In the case of Redaktilo, the editor is an object provided by a library.

## File

Also called "text file", to be opposed to "binary file".

See [Text](#text).

## Line

The unit with which **Redaktilo** works. It's a simple string which ends at the
line break:

* `\r\n` for texts created on Windows
* `\n` for texts created on the other operating systems

To make it easier for the developers, **Redaktilo** takes care of the line
break, so you should only provide it with a string stripped of it.

## Location

The given line number, to which you can relatively search or do something.

## Redaktilo

This means `editor` in esperanto. Technically `Tekstoredaktilo` should have been
used (`text editor`), but it was a bit too long for a project name.

## Text

Can contain:

* plain text
* JSON
* YAML
* PHP
* etc...

## Next readings

* [Extending](05-extending.md)
* [Exceptions](06-exceptions.md)

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
* [Reference](03-reference.md)
