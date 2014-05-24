# Vocabulary

* [Cursor](#cursor)
* [Line](#line)
* [Redaktilo](#redaktilo)
* [Text editor](#text-editor)
* [Text file](#text-file)

## Cursor

Also used to mean the current line.

An indicator used to know the position in the file. Currently a line number is
used, but it could change to:

* a line and a column
* a token's place in an array

This is useful when a pattern occurs many times in the file: it enables the
editor to select the wanted one.

The cursor also enables to manipulate the selected element.

## Line

The unit with which **Redaktilo** works. It's a simple string which ends at the
line break:

* `\r\n` for files created on Windows
* `\n` for files created on the other operating systems

To make it easier for the developers, **Redaktilo** takes care of the line
break, so you should only provide it with a string stripped of it.

## Redaktilo

This means `editor` in esperanto. Technically `Tekstoredaktilo` should have been
used (`text editor`), but it was a bit too long for a project name.

## Text editor

Also called editor.

A piece of software which is able to change the content of a file.
In the case of Redaktilo, the editor is an object provided by a library.

## Text file

To be opposed to "binary file". Can contain:

* plain text
* JSON
* YAML
* PHP
* etc...

## Previous readings

* [README](../README.md)
* [Tutorial](doc/01-tutorial.md)
* [Use cases](doc/02-use-cases.md)
* [Reference](doc/03-reference.md)
