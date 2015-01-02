# Backward Compatibility breaks in 2.0

This file describes the step to follow in order to migrate your projects to
Redaktilo 2.0.

The changes done in 2.0 can be found in [CHANGELOG](./CHANGELOG.md).

## Misc (1.1)

Here's a list of features removed, with their replacement:

* `Editor#has` has been removed, use `Editor#hasBelow` instead
* `LineReplaceCommand` no longer accepts strings, use `Text#setLine` instead

## Sanitizers (1.2)

The following commands now takes a `TextSanitizer` and a `LocationSanitizer`
mandatory argument in their constructor:

* `LineInsertAboveCommand`,
* `LineReplaceCommand`
* `LineInsertBelowCommand`
* `LineRemoveCommand`

## Exceptions (1.4)

The following exceptions have been removed:

* `Gnugat\Redaktilo\Command\CommandNotFoundException`
* `Gnugat\Redaktilo\Search\NotSupportedException`
* `Gnugat\Redaktilo\Search\PatternNotFoundException`
* `Gnugat\Redaktilo\InvalidLineNumberException`
* `Gnugat\Redaktilo\Service\DifferentLineBreaksFoundException`

Please use their equivalent (same name) from the following namespace:
`Gnugat\Redaktilo\Exception`.

## PHP and Number Search Removal (1.7)

The following classes have been removed:

* `LineNumberSearchStrategy`
* `PhpSearchStrategy`
* `Php/Token`
* `Php/TokenBuilder`
