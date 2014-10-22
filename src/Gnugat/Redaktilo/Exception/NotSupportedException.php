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

use Gnugat\Redaktilo\Search\NotSupportedException as BaseException;

/**
 * Thrown if the pattern given to SearchEngine isn't supported by any of its
 * registered strategies.
 *
 * @api
 *
 * @todo Move all code from deprecated parent to this class
 */
class NotSupportedException extends BaseException implements Exception
{
}
