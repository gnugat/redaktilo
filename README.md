# Redaktilo

A programatic editor:

    <?php

    use Redaktilo\Editor;

    $editor = new Editor();
    $file = $editor->openFile(__DIR__.'/tests/fixtures/edit-me.txt');

    if ($file->hasLine('grumpy')) {
        $file->addLineAfter('cat', 'grumpy');
    }

    $file->save();

Read more about this project in [its introduction](doc/01-introduction.md).

## Features

    Editor:

    [ ] opens existing file
    [ ] creates new file

    File:

    [ ] detects presence of line
    [ ] adds new line after existing one
    [ ] saves in the actual file

    Indentation:

    [ ] indents line with given depth
    [ ] unindents line with given depth
    [ ] guesses the identation depth of the given line

Find out how to use it with the [usage guide](doc/03-usage.md).

## Installation

To download and install this project, run the following command:

    curl -sS https://raw.github.com/gnugat/redaktilo/master/bin/installer.sh | sh

Learn more about the steps followed by the script by reading its [documentation](doc/02-installation.md).

## Further documentation

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/gnugat/redaktilo/releases)
* the file listing the [changes between versions](CHANGELOG.md)

You can find more documentation at the following links:

* [copyright and MIT license](LICENSE)
* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)
* [documentation directory](doc)
