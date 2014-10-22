<?php

namespace Gnugat\Redaktilo\Exception;

use Gnugat\Redaktilo\InvalidLineNumberException as BaseException;

/**
 * Thrown if the given line number isn't a positive integer strictly inferior to
 * the total number of lines in text.
 *
 * @api
 *
 * @todo Move all code from deprecated parent to this class
 */
class InvalidLineNumberException extends BaseException implements Exception
{
}
