<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Exception;

use Gnugat\Redaktilo\Command\CommandNotFoundException as BaseException;

/**
 * Thrown if the name given to CommandInvoker isn't in its collection.
 *
 * @api
 *
 * @todo Move all code from deprecated parent to this class
 */
class CommandNotFoundException extends BaseException implements Exception
{
}
