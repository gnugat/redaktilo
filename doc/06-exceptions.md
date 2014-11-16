# Exceptions

All the exceptions thrown in Redaktilo live in the `Gnugat\Redaktilo\Exception`
namespace and implement a common interface allowing you to catch any of them
easily:

```php
<?php

try {

    // any operations made with Redaktilo

} catch (\Gnugat\Redaktilo\Exception\Exception $e) { ... }
```

Redaktilo's exceptions all have a descriptive message allowing developers to
understand what went wrong. If the message is not enough, use their getter
to get more context.

Here's the list of the exceptions that can be thrown:

* [CommandNotFoundException](#commandnotfoundexception)
* [NotSupportedException](#notsupportedexception)
* [PatternNotFoundException](#patternnotfoundexception)
* [InvalidLineNumberException](#invalidlinenumberexception)
* [DifferentLineBreaksFoundException](#differentlinebreaksfoundexception)
* [FileNotFoundException](#filenotfoundexception)
* [IOException](#ioexception)
* [InvalidArgumentException](#invalidargumentexception)

## CommandNotFoundException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class CommandNotFoundException extends \Exception implements Exception
{
    public function getName();
    public function getCommands();
}
```

## NotSupportedException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class NotSupportedException extends \Exception implements Exception
{
    public function getPattern();
    public function getSearchStrategies();
}
```

## PatternNotFoundException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class PatternNotFoundException extends \Exception implements Exception
{
    public function getPattern();
    public function getText();
}
```

## InvalidLineNumberException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class InvalidLineNumberException extends \InvalidArgumentException implements Exception
{
    public function getLineNumber();
    public function getText();
}
```

## DifferentLineBreaksFoundException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class DifferentLineBreaksFoundException extends \Exception implements Exception
{
    public function getString();
    public function getNumberLineBreakOther();
    public function getNumberLineBreakWindows();
}
```

## FileNotFoundException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class FileNotFoundException extends \RuntimeException implements Exception
{
    public function getPath();
}
```

## IOException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class IOException extends \RuntimeException implements Exception
{
    public function getPath();
}
```

## InvalidArgumentException

```php
<?php

namespace Gnugat\Redaktilo\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements Exception
{
}
```

## Previous readings

* [README](../README.md)
* [Tutorial](01-tutorial.md)
* [Use cases](02-use-cases.md)
* [Reference](03-reference.md)
* [Vocabulary](04-vocabulary.md)
* [Extending](05-extending.md)
