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

use Gnugat\Redaktilo\Service\DifferentLineBreaksFoundException as BaseException;

/**
 * Thrown if the string given to LineBreak service contains different line breaks.
 *
 * @api
 *
 * @todo Move all code from deprecated parent to this class
 */
class DifferentLineBreaksFoundException extends BaseException implements Exception
{
}
