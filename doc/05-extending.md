# Redaktilo Extension Points

* [Search](#search)
    * [SearchStrategy API](#searchstrategy-api)
* [Commands](#commands)
    * [Command API](#command-api)

## Search

Use the `EditorBuilder` in order to register custom `SearchStrategy`
implementations:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$strategy = new MyCustomStrategy();

$builder = EditorFactory::createBuilder();
$builder->addSearchStrategy($strategy);

$editor = $builder->getEditor();
```

The strategy will then be automatically used (if it's the first to support the
given pattern) when calling one of the following `Editor` method:

* `jumpAbove`
* `jumpBelow`
* `has`

If your strategy should be used instead of another already registered strategy
(ie. they support the same pattern), you can give it a higher priority:

```php
$builder->addSearchStrategy($strategy, 50);
```

**Important**: The higher the priority is, the sooner the strategy will be
returned if it supports the given pattern.

**Note**:A default priority of 0 is assigned to strategies if you don't specify
it.

### SearchStrategy API

A lookup strategy supporting a specific kind of pattern:

```php
<?php

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Text;

interface SearchStrategy
{
    public function findAbove(Text $text, $pattern, $location = null);
    public function findBelow(Text $text, $pattern, $location = null);

    public function supports($pattern);
}
```

Implementations provided out of the box are:

* `PhpSearchStrategy`: PHP token search, supports array of `Gnugat\Redaktilo\Search\Php\Token`
* `LineRegexSearchStrategy`: regular expression search, supports regex (`/pattern/`)
* `SameSearchStrategy`: strict equality search (`===`), supports strings

## Commands

Use the `EditorBuilder` in order to register custom `Command` implementations:

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Gnugat\Redaktilo\EditorFactory;

$command = new DoSomethingCommand();

$builder = EditorFactory::createBuilder();
$builder->addCommand($command);
$commandInvoker = $builder->getCommandInvoker();
$editor = $builder->getEditor();
$file = $editor->open('/tmp/menu_spam.txt', true);

$commandInvoker->run('do_something', array('text' => $file));
```

Currently commands cannot be added dynamically to `Editor`, but its following
methods uses the `CommandInvoker`:

* `insertAbove`
* `insertBelow`
* `remove`
* `replace`

### Command API

Executes a task with the given input:

```php
<?php

namespace Gnugat\Redaktilo\Command;

interface Command
{
    public function getName();
    public function execute(array $input);
}
```

Implementations provided out of the box are:

* `LineInsertAboveCommand`: `text`, `addition` and optional `location` (name: `insert_above`)
* `LineInsertBelowCommand`: `text`, `addition` and optional `location` (name: `insert_below`)
* `LineRemoveCommand`: `text` and optional `location` (name: `remove`)
* `LineReplaceCommand`: `text`, `replacement` and optional `location` (name: `replace`)

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
* [Reference](03-reference.md)
* [Vocabulary](04-vocabulary.md)
