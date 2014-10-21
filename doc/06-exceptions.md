# Exceptions

Redaktilo's exceptions all have a descriptive message allowing developers to
understand what went wrong. If the message is not enough, use their getter
to get more context.

Here's the list of the available getters:

* [CommandNotFoundException](#commandnotfoundexception)
* [NotSupportedException](#notsupportedexception)
* [PatternNotFoundException](#patternnotfoundexception)
* [InvalidLineNumberException](#invalidlinenumberexception)
* [DifferentLineBreaksFoundException](#differentlinebreaksfoundexception)

## CommandNotFoundException

```php
<?php

namespace Gnugat\Redaktilo\Command;

class CommandNotFoundException extends \Exception
{
    public function getName();
    public function getCommands();
}
```

## NotSupportedException

```php
<?php

namespace Gnugat\Redaktilo\Search;

class NotSupportedException extends \Exception
{
    public function getPattern();
    public function getSearchStrategies();
}
```

## PatternNotFoundException

```php
<?php

namespace Gnugat\Redaktilo\Search;

class PatternNotFoundException extends \Exception
{
    public function getPattern();
    public function getText();
}
```

## InvalidLineNumberException

```php
<?php

namespace Gnugat\Redaktilo;

class InvalidLineNumberException extends \InvalidArgumentException
{
    public function getLineNumber();
    public function getText();
}
```

## DifferentLineBreaksFoundException

```php
<?php

namespace Gnugat\Redaktilo\Service;

class DifferentLineBreaksFoundException extends \Exception
{
    public function getString();
    public function getNumberLineBreakOther();
    public function getNumberLineBreakWindows();
}
```

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
* [Reference](03-reference.md)
* [Vocabulary](04-vocabulary.md)
* [Extending](05-extending.md)
